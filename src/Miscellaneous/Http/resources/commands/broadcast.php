<?php

use Oraculum\Cli\Macros\Console;

Console::command("broadcast")
       ->handle([\Miscellaneous\Http\Commands\BroadcastCommand::class, "handle"])
       ->describe("Starts an broadcast server specified by [--address=0.0.0.0] and [--port=8080].");