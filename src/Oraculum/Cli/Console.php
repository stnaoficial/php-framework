<?php

namespace Oraculum\Cli;

use Oraculum\Cli\Support\Io as IoSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use UnexpectedValueException;

final class Console extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var array<string, Command> The registered commands.
     */
    private $commands = [];

    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    public function __construct()
    {
        $helpCommand = new Command("help", [\Oraculum\Cli\Commands\HelpCommand::class, "handle"], "Print help information.");

        $this->setCommand($helpCommand);
    }

    /**
     * Writes a message.
     *
     * @param string $message The message to be written.
     *
     * @return string The message that was written.
     */
    public function write($message = '')
    {
        IoSupport::output($message);

        return $message;
    }

    /**
     * Writes a message on a new line.
     *
     * @param string $message The message to be written.
     * @param int    $break   The number of lines to break.
     *
     * @return string The message that was written.
     */
    public function writeLine($message = '', $break = 1)
    {
        return $this->write($message .= str_repeat(PHP_EOL, $break));
    }

    /**
     * Ask a question.
     *
     * @param string|null $question The question to be asked.
     * @param string|null $default  The default answer.
     * @param bool        $strict   If the input needs to be strictly converted to its respective type.
     *
     * @return string The answer to the question.
     */
    public function ask($question = null, $default = null, $strict = false)
    {
        if (!is_null($question)) {
            $this->write($question);
        }

        $answer = IoSupport::input($strict);

        if (strlen($answer) === 0 && !is_null($default)) {
            return $default;
        }

        return $answer;
    }

    /**
     * Checks if a command exists.
     *
     * @param string $signature The signature of the command.
     *
     * @return bool Returns `true` if the command exists, `false` otherwise.
     */
    public function hasCommand($signature)
    {
        return isset($this->commands[$signature]);
    }

    /**
     * Gets a command.
     *
     * @param string $signature The signature of the command.
     *
     * @return Command|null Returns the command if it exists, `null` otherwise.
     */
    public function getCommand($signature)
    {
        return $this->hasCommand($signature)? $this->commands[$signature] : null;
    }

    /**
     * Gets all commands.
     *
     * @return array<string, Command> The list of commands.
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Sets a command.
     *
     * @param Command $command The command to be set.
     *
     * @return void
     */
    public function setCommand($command)
    {
        $this->commands[$command->getSignature()] = $command;
    }

    /**
     * Handles a the given request.
     *
     * @param Request $request The request to be handled.
     *
     * @throws UnexpectedValueException If the given command does not exist.
     *
     * @return Command The command to be executed.
     */
    public function handleRequest($request)
    {
        // Check if the current request has a command or not.
        // If the request does not have a command, returns the help command.
        if (!$request->hasCommand()) {
            return $this->getCommand("help");
        }

        $command = $this->getCommand($request->getCommand()) or throw new UnexpectedValueException(sprintf(
            "Command [%s] not found.", $request->getCommand()
        ));

        return $command;
    }
}
