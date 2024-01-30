<?php

namespace Oraculum\Cli;

use Oraculum\Cli\Support\Request as RequestSupport;
use Oraculum\Contracts\FromCapture;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Request extends PrimitiveObject implements FromCapture
{
    /**
     * The command to execute.
     * 
     * @var string|null
     */
    private $command;

    /**
     * The options to pass to the command.
     * 
     * @var array
     */
    private $options;

    /**
     * The flags to pass to the command.
     * 
     * @var array
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
     * @return bool
     */
    public function hasCommand()
    {
        return !is_null($this->command);
    }

    /**
     * Returns the command to execute.
     * 
     * @return string|null
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Checks if the request has options.
     * 
     * @return bool
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
     * @return bool
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Returns the option to pass to the command.
     * 
     * @param string $name The name of the option.
     * 
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * Returns the options to pass to the command.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Checks if the request has flags.
     * 
     * @return bool
     */
    public function hasFlags()
    {
        return !empty($this->flags);
    }

    /**
     * Checks if the request has a flag.
     * 
     * @param string $name The name of the flag.
     * 
     * @return bool
     */
    public function hasFlag($name)
    {
        return in_array($name, $this->flags);
    }

    /**
     * Returns the flags to pass to the command.
     * 
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }
}