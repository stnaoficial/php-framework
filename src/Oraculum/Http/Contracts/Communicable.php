<?php

namespace Oraculum\Http\Contracts;

interface Communicable
{
    /**
     * Sends the communication.
     * 
     * @return void
     */
    public function send();
}