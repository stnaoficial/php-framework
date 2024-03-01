<?php

namespace Oraculum\Cli\Constracts;

interface CommandHandler
{
    /**
     * Handles the command.
     * 
     * @return void
     */
    public function handle();
}