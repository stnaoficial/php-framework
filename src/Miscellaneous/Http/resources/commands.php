<?php

use Oraculum\Cli\Macros\Console;

Console::command("serve")
       ->handle([\Miscellaneous\Http\Commands\ServeCommand::class, "handle"])
       ->describe("Starts an development server specified by [--host=0.0.0.0], [--port=80] and [--output-logs].");

Console::command("broadcast")
       ->handle([\Miscellaneous\Http\Commands\BroadcastCommand::class, "handle"])
       ->describe("Starts an broadcast server specified by [--address=0.0.0.0] and [--port=0].");

Console::command("route:list")
       ->handle([\Miscellaneous\Http\Commands\RouteListCommand::class, "handle"])
       ->describe("List all registered routes.");