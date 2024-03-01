<?php

namespace Oraculum\Cli;

use Oraculum\Cli\Support\Request as RequestSupport;
use Oraculum\Support\Contracts\FromCapture;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Request extends PrimitiveObject implements FromCapture
{
    /**
     * @var string|null $command The command to execute.
     */
    private $command;

    /**
     * @var array $options The options to pass to the command.
     */
    private $options;

    /**
     * @var array $flags The flags to pass to the command.
     */
    private $flags;

    /**
     * Creates a new instance of the class.
     * 
     * @param string|null $command The command to execute.
     * @param array       $options The options to pass to the command.
     * @param array       $flags   The flags to pass to the command.
     * 
     * @return void
     */
    public function __construct($command = null, $options = [], $flags = [])
    {
        $this->command = $command;
        $this->options = $options;
        $this->flags   = $flags;
    }

     /**
     * Creates a new instance from an capture.
     * 
     * @return self The new instance.
     */
    public static function fromCapture()
    {
        return new self(
            RequestSupport::command(),
            RequestSupport::options(),
            RequestSupport::flags()
        );
    }

    /**
     * Returns if the request has a command.
     * 
     * @return bool Returns `true` if the request has a command and `false` otherwise.
     */
    public function hasCommand()
    {
        return !is_null($this->command);
    }

    /**
     * Returns the command to execute.
     * 
     * @return string|null Returns the request command.
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Checks if the request has options.
     * 
     * @return bool Returns `true` if the request has options and `false` otherwise.
     */
    public function hasOptions()
    {
        return !empty($this->options);
    }

    /**
     * Checks if the request has an option.
     * 
     * @param string $name The name of the option.
     * 
     * @return bool Returns `true` if the option exists and `false` otherwise.
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Returns the option to pass to the command.
     * 
     * @template TValue of string
     * 
     * @param string $name The name of the option.
     * 
     * @return TValue|null Returns the option value if it exists and `null` otherwise.
     */
    public function getOption($name)
    {
        return $this->options[$name] ?? null;
    }

    /**
     * Asks for an option to pass to the command.
     * 
     * @template TValue of string
     * 
     * @param string  $name     The name of the option.
     * @param Console $console  The console instance.
     * @param string  $question The question to ask.
     * @param TValue  $default  The default answer.
     * @param bool    $strict   If the input needs to be strictly converted to its respective type.
     * 
     * @return TValue Returns the option value or the answer value.
     */
    public function askOption($name, $console, $question = null, $default = null, $strict = false)
    {
        if (!$this->hasOption($name)) {
            $this->options[$name] = $console->ask($question, $default, $strict);
        }

        return $this->getOption($name);
    }

    /**
     * Gets the default value of an option until it is set.
     * 
     * @template TValue of string
     * 
     * @param string $name    The name of the option.
     * @param TValue $default The default value.
     * 
     * @return TValue|null Returns the option value or the default value.
     */
    public function untilOption($name, $default = null)
    {
        if (!$this->hasOption($name)) {
            $this->options[$name] = $default;
        }

        return $this->getOption($name);
    }

    /**
     * Returns the options to pass to the command.
     * 
     * @return array Returns the request options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Checks if the request has flags.
     * 
     * @return bool Returns `true` if the request has flags and `false` otherwise.
     */
    public function hasFlags()
    {
        return !empty($this->flags);
    }

    /**
     * Returns the flags to pass to the command.
     * 
     * @return array Returns the request flags.
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Checks if the request has a flag.
     * 
     * @param string $name The name of the flag.
     * 
     * @return bool Returns `true` if the flag exists and `false` otherwise.
     */
    public function hasFlag($name)
    {
        return in_array($name, $this->flags);
    }
}