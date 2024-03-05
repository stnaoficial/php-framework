<?php

namespace Miscellaneous\Http\Commands;

use Miscellaneous\Http\WebSocketAuthenticationAgent;
use Oraculum\Cli\Console;
use Oraculum\Cli\Constracts\CommandHandler;
use Oraculum\Cli\Request;
use Oraculum\Json\Json;
use Oraculum\Socket\Client as SocketClient;
use Oraculum\Socket\Server as SocketServer;
use Oraculum\Support\Primitives\PrimitiveObject;

final class BroadcastCommand extends PrimitiveObject implements CommandHandler
{
    /**
     * @var Console $console The console instance.
     */
    private $console;

    /**
     * @var Request $request The CLI request instance.
     */
    private $request;

    /**
     * Creates a new instance of the class.
     * 
     * @param Console $console The console instance.
     * @param Request $request The CLI request instance.
     * 
     * @return void
     */
    public function __construct(Console $console, Request $request)
    {
        $this->console = $console;
        $this->request = $request;
    }

    /**
     * Creates the socket server.
     * 
     * @return SocketServer Returns the socket server instance.
     */
    private function createServer()
    {
        $address = $this->request->untilOption("address", "0.0.0.0");
        $port    = $this->request->untilOption("port", 8080);

        $server = new SocketServer($address, $port, SocketServer::REUSE);

        return $server;
    }

    /**
     * Gets the server address.
     * 
     * @param SocketServer $server The socket server instance.
     * 
     * @return string Returns the server address.
     */
    private function getAddress($server)
    {
        return sprintf("%s:%s", $server->getAddress(), $server->getPort());
    }

    /**
     * Handles the command.
     * 
     * @return void
     */
    public function handle()
    {
        $server = $this->createServer();

        $this->console->writeLine(sprintf("Broadcast server started at ws://%s.", $this->getAddress($server)), 2);

        $agent = new WebSocketAuthenticationAgent;

        $server->serve(function (SocketClient $client) use ($agent) {
            $handshake = $agent->authenticate($client);

            $client->send($handshake);

            // Wait for a message from the client.
            while (true) {
                $received = new Json($client->receive(2048, true));

                $this->console->writeLine("Received: ");
                $this->console->writeLine($received, 2);

                $client->send($sent = Json::fromArray([
                    "message" => "Pong!"
                ]), true);

                $this->console->writeLine("Sent: ");
                $this->console->writeLine($sent, 2);

                sleep(1);
            }
        });
    }
}