<?php

namespace Miscellaneous\Http;

use Oraculum\Http\Enums\StatusCode;
use Oraculum\Socket\Client;
use Oraculum\Socket\Contracts\AuthenticationAgent;
use Oraculum\Support\Primitives\PrimitiveObject;

final class WebSocketAuthenticationAgent extends PrimitiveObject implements AuthenticationAgent
{
    private const AUTH_UUID = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";

    /**
     * Authenticate an socket client connection.
     * 
     * @param Client $client The client to authenticate.
     * 
     * @return string|false Returns the authentication metadata on success and false on failure.
     */
    public function authenticate($client)
    {
        // Matches the Sec-WebSocket-Key header in the given data.
        // It is then used to generate the handshake hash.
        preg_match("#Sec-WebSocket-Key: (.*)\r\n#", $client->receive(2048), $matches);

        if (!isset($matches[1])) {
            return false;
        }

        // Converts the Sec-WebSocket-Key header to a SHA1 hash.
        $hash = base64_encode(pack("H*", sha1($matches[1] . self::AUTH_UUID)));

        // Prepends the headers to the data to be sent.
        // This headers contains the hash information required to establish a connection.
        $header = Header::new(StatusCode::SWITCHING_PROTOCOLS, [
            "Upgrade"               => "websocket",
            "Connection"            => "Upgrade",
            "Sec-WebSocket-Version" => "13",
            "Sec-WebSocket-Accept"  => $hash
        ]);

        return $header->toString();
    }
}