<?php

namespace Oraculum\Socket\Contracts;

use Oraculum\Socket\Client;

interface AuthenticationAgent
{
    /**
     * Authenticate an socket client connection.
     * 
     * @param Client $client The client to authenticate.
     * 
     * @return string|false Returns the authentication metadata on success and false on failure.
     */
    public function authenticate($client);
}