<?php

use Oraculum\Cli\Macros\Console;

Console::command("serve")
       ->handle([\Miscellaneous\Http\Commands\ServeCommand::class, "handle"])
       ->describe("Starts an development server specified by [--host=0.0.0.0], [--port=80] and [--output-logs].");