<?php

namespace Oraculum\Socket;

use Oraculum\Socket\Exceptions\InvalidSocketException;
use Socket;

final class Client
{
    /**
     * @var resource|Socket $resource The internal socket client resource.
     */
    private $resource;

    /**
     * Creates a new instance of the class.
     * 
     * @param resouce|Socket $resource The internal socket client resource.
     * 
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
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
     * Gets the internal socket client resource.
     * 
     * @return resource|Socket The internal socket client resource.
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Unmasks the data.
     * 
     * @param string $data The data to unmask.
     * 
     * @return string The unmasked data.
     */
    private function unmask($data)
    {
        $length = ord($data[1]) & 127;

        if($length == 126) {
            $masks = substr($data, 4, 4);
            $value = substr($data, 8);

        } elseif($length == 127) {
            $masks = substr($data, 10, 4);
            $value = substr($data, 14);

        } else {
            $masks = substr($data, 2, 4);
            $value = substr($data, 6);
        }

        $data = "";

        for ($i = 0; $i < strlen($value); ++$i) {
            $data .= $value[$i] ^ $masks[$i % 4];
        }

        return $data;
    }

    /**
     * Receives data from the client connection.
     * 
     * @param int  $length The length of the data to receive.
     * @param bool $unmask Whether to unmask the data or not.
     * 
     * @throws InvalidSocketException If the socket client connection cannot receive data.
     * 
     * @return string Returns the data received from the socket client.
     */
    public function receive($length, $unmask = false)
    {
        $data = @socket_read($this->resource, $length) or throw new InvalidSocketException("Unable to receive data from client connection.", socket: $this->resource);

        return $unmask? $this->unmask($data) : $data;
    }

    /**
     * Masks the data.
     * 
     * @param string $data The data to mask.
     * 
     * @return string The masked data.
     */
    private function mask($data)
    {
        $b1 = 0x80 | (0x1 & 0x0f);

        $length = strlen($data);

        if($length <= 125) {
            $header = pack('CC', $b1, $length);

        } elseif($length > 125 && $length < 65536) {
            $header = pack('CCn', $b1, 126, $length);

        } elseif($length >= 65536) {
            $header = pack('CCNN', $b1, 127, $length);
        }

        return $header . $data;
    }

    /**
     * Sends data to the socket client.
     * 
     * @param string $data The data to send.
     * @param bool   $mask Whether to mask the data or not.
     * 
     * @throws InvalidSocketException If the socket client connection cannot send data.
     * 
     * @return int Returns the number of bytes sent.
     */
    public function send($data, $mask = false)
    {
        $data = $mask? $this->mask($data) : $data;

        $bytes = @socket_write($this->resource, $data, strlen($data)) or throw new InvalidSocketException("Unable to send data to client connection.", socket: $this->resource);

        return $bytes;
    }

    /**
     * Checks if the socket client is open.
     * 
     * @return bool Returns `true` if the socket client is open and `false` otherwise.
     */
    public function  isOpen()
    {
        return is_resource($this->resource);
    }

    /**
     * Closes the socket client.
     * 
     * @return bool Returns `true` on success and `false` on failure.
     */
    public function close()
    {
        if (!$this->isOpen()) {
            return false;
        }

        @socket_close($this->resource);

        return true;
    }
}