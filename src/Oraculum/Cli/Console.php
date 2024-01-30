<?php

namespace Oraculum\Cli;

use Oraculum\Cli\Support\Console as ConsoleSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\System as SystemSupport;
use Oraculum\Support\Traits\GloballyAvailable;
use UnexpectedValueException;

final class Console extends PrimitiveObject
{
    use GloballyAvailable;

    const TEXT_DECORATION_BOLD          = 1;
    const TEXT_DECORATION_ITALIC        = 2;
    const TEXT_DECORATION_UNDERLINE     = 4;
    const TEXT_DECORATION_STRIKETHROUGH = 8;

    const TEXT_BLACK   = 16;
    const TEXT_RED     = 32;
    const TEXT_GREEN   = 64;
    const TEXT_YELLOW  = 128;
    const TEXT_BLUE    = 256;
    const TEXT_MAGENTA = 512;
    const TEXT_CYAN    = 1024;
    const TEXT_WHITE   = 2048;

    const TEXT_BACKGROUND_BLACK   = 4096;
    const TEXT_BACKGROUND_RED     = 8192;
    const TEXT_BACKGROUND_GREEN   = 16384;
    const TEXT_BACKGROUND_YELLOW  = 32768;
    const TEXT_BACKGROUND_BLUE    = 65536;
    const TEXT_BACKGROUND_MAGENTA = 131072;
    const TEXT_BACKGROUND_CYAN    = 262144;
    const TEXT_BACKGROUND_WHITE   = 524288;

    /**
     * Defines the associated code for each flag.
     *
     * @var array<int, int>
     */
    private $formats = [
        self::TEXT_DECORATION_BOLD          => 1,
        self::TEXT_DECORATION_ITALIC        => 3,
        self::TEXT_DECORATION_UNDERLINE     => 4,
        self::TEXT_DECORATION_STRIKETHROUGH => 9,

        self::TEXT_BLACK                    => 30,
        self::TEXT_RED                      => 31,
        self::TEXT_GREEN                    => 32,
        self::TEXT_YELLOW                   => 33,
        self::TEXT_BLUE                     => 34,
        self::TEXT_MAGENTA                  => 35,
        self::TEXT_CYAN                     => 36,
        self::TEXT_WHITE                    => 37,

        self::TEXT_BACKGROUND_BLACK         => 40,
        self::TEXT_BACKGROUND_RED           => 41,
        self::TEXT_BACKGROUND_GREEN         => 42,
        self::TEXT_BACKGROUND_YELLOW        => 43,
        self::TEXT_BACKGROUND_BLUE          => 44,
        self::TEXT_BACKGROUND_MAGENTA       => 45,
        self::TEXT_BACKGROUND_CYAN          => 46,
        self::TEXT_BACKGROUND_WHITE         => 47,
    ];

    /**
     * The registered commands.
     *
     * @var array<string, Abstracts\Command>
     */
    private $commands = [];

    /**
     * Formats a message with the specified flags.
     *
     * @param string $message The message to be formatted.
     * @param int    $flags   The flags to apply during formatting.
     *
     * @return string The formatted message.
     */
    private function format($message, $flags = 0)
    {
        $formats = [];

        foreach ($this->formats as $flag => $format) {
            if ($flags & $flag) {
                $formats[] = $format;
            }
        }

        $formats = implode(';', $formats);

        return "\e[" . $formats . "m" . $message . "\e[0m";
    }

    /**
     * Writes a message.
     *
     * @param string $message The message to be written.
     * @param int    $flags   The flags to be applied to the message.
     *
     * @return string The message that was written.
     */
    public function write($message = '', $flags = 0)
    {
        $message = $this->format($message, $flags);

        echo $message;

        return $message;
    }

    /**
     * Writes a message on a new line.
     *
     * @param string $message The message to be written.
     * @param int    $flags   The flags to be applied to the message.
     *
     * @return string The message that was written.
     */
    public function writeLine($message = '', $flags = 0)
    {
        return $this->write($message .= PHP_EOL, $flags);
    }

    /**
     * Ask a question.
     *
     * @param string|null $question The question to be asked.
     * @param int         $flags   The flags to be applied to the message.
     * @param bool        $strict  If the answer must be strict.
     *
     * @return string The answer to the question.
     */
    public function ask($question = null, $flags = 0, $strict = false)
    {
        if (!is_null($question)) {
            $this->write($question, $flags);
        }

        $answer = SystemSupport::input();

        if ($strict) {
            return ConsoleSupport::strict($answer);
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
     * @throws UnexpectedValueException If the request is invalid.
     *
     * @return Abstracts\Command The command to be executed.
     */
    public function handleRequest($request)
    {
        if (!$request->hasCommand()) {
            throw new UnexpectedValueException(
                "No command supplied."
            );
        }

        if (!$command = $this->getCommand($request->getCommand())) {
            throw new UnexpectedValueException(sprintf(
                "Command [%s] not found.",
                $request->getCommand()
            ));
        }

        return $command;
    }
}
