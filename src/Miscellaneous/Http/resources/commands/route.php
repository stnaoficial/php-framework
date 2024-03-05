<?php

use Oraculum\Cli\Macros\Console;

Console::command("route:list")
       ->handle([\Miscellaneous\Http\Commands\RouteListCommand::class, "handle"])
       ->describe("List all registered routes.");