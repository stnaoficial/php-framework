<?php

namespace Oraculum\Socket;

use Oraculum\Socket\Exceptions\InvalidSocketException;
use Socket;

final class Server
{
    public const REUSE = 1;

    /**
     * @var resource|Socket $resource The internal socket server resource.
     */
    private $resource;

    /**
     * @var string $address The server address.
     */
    private $address;

    /**
     * @var int $port The server port.
     */
    private $port;

    /**
     * @var array<int, Client> $clients The clients connected to the server.
     */
    private $clients = [];

    /**
     * Creates a new instance of the class.
     * 
     * @param string $address The address of the server.
     * @param int    $port    The port of the server.
     * @param int    $flags   The flags of the socket.
     * 
     * @throws InvalidSocketException If the some error occurs during the creation of the socket.
     * 
     * @return void
     */
    public function __construct($address, $port = 0, $flags = 0)
    {
        $this->resource = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or throw new InvalidSocketException("Unable to create socket.");

        // Sets the socket option to reuse the address.
        // p.s.
        // SOL_SOCKET   - The socket level.
        // SO_REUSEADDR - Allows the socket to be bound to an address that is already in use.
        ($flags & self::REUSE) && @socket_set_option($this->resource, SOL_SOCKET, SO_REUSEADDR, 1);

        // Sets the server address and port to the socket.
        // If the port is not specified, it will be set to 0 to let the operating system choose the port.
        @socket_bind($this->resource, $this->address = $address, $this->port = $port) || throw new InvalidSocketException("Unable to bind address and port to the socket.", socket: $this->resource);
    }

    /**
     * Destroys the instance of the class.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Gets the internal socket server resource.
     * 
     * @return resource|Socket The internal socket server resource.
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Gets the address of the socket server.
     * 
     * @return string The address of the socket server.
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Gets the port of the socket server.
     * 
     * @return int The port of the socket server.
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Gets the clients connected to the socket server.
     * 
     * @return array<int, Client> The clients connected to the socket server.
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * Starts listening for incoming connections.
     * 
     * @param int $backlog The backlog of the socket server.
     * 
     * @throws InvalidSocketException If the socket server cannot start listening for connections.
     * 
     * @return void
     */
    private function listen($backlog = 0)
    {
        @socket_listen($this->resource, $backlog) || throw new InvalidSocketException("Unable to start listening for connections.", socket: $this->resource);
    }

    /**
     * Accepts an incoming connection.
     * 
     * @throws InvalidSocketException If the socket connection could not be accepted.
     * 
     * @return Client The accepted socket client.
     */
    private function accept()
    {   
        $resource = @socket_accept($this->resource) or throw new InvalidSocketException("Unable to accept socket connection.", socket: $this->resource);

        $this->clients[] = $client = new Client($resource);

        return $client;
    }

    /**
     * Serves the socket server.
     * 
     * @param callable $handler The callback function to handle incoming connections.
     * 
     * @throws InvalidSocketException If some error occurs while serving the socket server.
     * 
     * @return void
     */
    public function serve($handler)
    {
        // Starts listening for incoming connections.
        $this->listen();

        while (true) {
            // Accepts an incoming connection.
            $client = $this->accept();

            // Handle the incoming connection with the given callback function.
            call_user_func($handler, $client);

            $client->close();
        }

        $this->close();
    }

    /**
     * Manually tries to connect a client to the server.
     * 
     * @param Client $client  The socket client.
     * @param int    $timeout The timeout of the socket connection in seconds.
     * 
     * @throws InvalidSocketException If the socket connection could not be established.
     * 
     * @return void
     */
    public function connect(Client $client, $timeout = 0)
    {
        $resource = $client->getResource();

        // Sets the socket to non-blocking mode.
        @socket_set_nonblock($resource) or throw new InvalidSocketException("Unable to nonblock client socket.", socket: $resource);

        // The start time of the socket connection.
        $start = time();

        // Tries to connect to the server address and port.
        while (!@socket_connect($resource, $this->address, $this->port)) {
            // Check if the socket error.
            // p.s.
            // EINPROGRESS - The socket is connecting.
            // EALREADY    - The socket is already connected.
            if (!in_array(socket_last_error($resource), [SOCKET_EINPROGRESS, SOCKET_EALREADY])) {
                throw new InvalidSocketException("Unable to connect client to the server.", socket: $resource);
            }

            // Checks if the timeout has been reached.
            if ((time() - $start) > $timeout) {
                throw new InvalidSocketException("Client connection timeout.", socket: $resource);
            }

            sleep(1);
        }

        // Sets the socket to blocking mode.
        @socket_set_block($resource) or throw new InvalidSocketException("Unable to block client socket.", socket: $resource);

        // Appends the socket client to the list of connected clients.
        $this->clients[] = $client;
    }

    /**
     * Checks if the socket server is open.
     * 
     * @return bool Returns `true` if the socket server is open and `false` otherwise.
     */
    public function isOpen()
    {
        return is_resource($this->resource);
    }

    /**
     * Closes the socket server and all connected clients.
     * 
     * @return bool Returns `true` on success and `false` on failure.
     */
    public function close()
    {
        if (!$this->isOpen()) {
            return false;
        }

        foreach ($this->clients as $client) {
            $client->close();
        }

        @socket_close($this->resource);

        return true;
    }
}