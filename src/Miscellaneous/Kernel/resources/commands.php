<?php

use Oraculum\Cli\Macros\Console;

Console::command("license")
       ->handle([\Miscellaneous\Kernel\Commands\LicenseCommand::class, "handle"])
       ->describe("Outputs the framework license.");