<?php

namespace Oraculum\Cli;

use Oraculum\Cli\Commands\HelpCommand;
use Oraculum\Cli\Support\Io as IoSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use UnexpectedValueException;

final class Console extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var string The console buffer.
     */
    private $buffer = '';

    /**
     * @var array<string, Abstracts\Command> The registered commands.
     */
    private $commands = [];

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
     * @param int    $break   The number of new lines to break.
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
     * @param bool        $strict   If the answer needs to be strictly converted to its respective type.
     *
     * @return string The answer to the question.
     */
    public function ask($question = null, $strict = false)
    {
        if (!is_null($question)) {
            $this->write($question);
        }

        $answer = IoSupport::input($strict);

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
     * @return Abstracts\Command|null Returns the command if it exists, `null` otherwise.
     */
    public function getCommand($signature)
    {
        if ($this->hasCommand($signature)) {
            return $this->commands[$signature];
        }

        return null;
    }

    /**
     * Gets all commands.
     *
     * @return array<string, Abstracts\Command> The list of commands.
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Sets a command.
     *
     * @param Abstracts\Command $command The command to be set.
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
     * @return Abstracts\Command The command to be executed.
     */
    public function handleRequest($request)
    {
        // Sets the help command if is not already set.
        // This is a good approach because it makes it easier to identify the
        // commands available to the user when no command is given. 
        if (!$this->hasCommand("help")) {
            $this->setCommand(new HelpCommand($this->commands));
        }

        // Check if the current request has a command or not.
        // If the request does not have a command, returns the help command.
        if (!$request->hasCommand()) {
            return $this->getCommand("help");
        }

        // If the given command does not exist, throws an exception.
        if (!$command = $this->getCommand($request->getCommand())) {
            throw new UnexpectedValueException(sprintf(
                "Command [%s] not found.", $request->getCommand()
            ));
        }

        return $command;
    }
}
