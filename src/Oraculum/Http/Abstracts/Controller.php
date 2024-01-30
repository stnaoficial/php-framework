<?php

namespace Oraculum\Http\Abstracts;

use Oraculum\Support\Primitives\PrimitiveObject;

abstract class Controller extends PrimitiveObject
{
    /**
     * Receive the given request.
     * 
     * @param \Oraculum\Http\Request $request The request to be received.
     * 
     * @return void
     */
    public abstract function receive($request);

    /**
     * Transmit the given response.
     * 
     * @param \Oraculum\Http\Response $response The response to be transmitted.
     * 
     * @return void
     */
    public abstract function transmit($response);

    /**
     * Controls the request.
     * 
     * @param \Oraculum\Http\Request $request The request to be controlled.
     * 
     * @return \Oraculum\Http\Response The response to be sent.
     */
    public abstract function control($request);
}