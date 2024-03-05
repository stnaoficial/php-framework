<?php

use Oraculum\Cli\Macros\Console;

Console::command("create:class")
       ->handle([\Miscellaneous\Stub\Commands\CreateClassCommand::class, "handle"])
       ->describe("Create a new class with the especified [--namespace], [--classes=<name>,...>] and [--dirname].");

Console::command("create:interface");
Console::command("create:trait");
Console::command("create:enum");