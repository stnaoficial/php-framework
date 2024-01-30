<?php

namespace Oraculum\Cli\Support;

use InvalidArgumentException;
use Oraculum\Support\Traits\NonInstantiable;

final class Request
{
    use NonInstantiable;

    /**
     * Determines if the given argument is an option.
     *
     * @param string $argument The argument to check.
     * 
     * @return bool Returns true if the argument is an option, false otherwise.
     */
    public static function isOption($argument)
    {
        if (preg_match('/^--[^--]/', $argument)) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the given argument is a flag.
     *
     * @param string $argument The argument to check.
     *
     * @return bool Returns true if the argument is a flag, false otherwise.
     */
    public static function isFlag($argument)
    {
        if (preg_match('/^-[^-]/', $argument)) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the filename being executed.
     *
     * @return string|null The filename or `null` if not found.
     */
    public static function filename()
    {
        if (isset($GLOBALS['argv'], $GLOBALS['argc']) && $GLOBALS['argc'] > 0) {
            return $GLOBALS['argv'][0];
        }

        return null;
    }

    /**
     * Retrieves the command line arguments passed.
     *
     * @return array The command line arguments.
     */
    public static function arguments()
    {
        if (isset($GLOBALS['argv'], $GLOBALS['argc']) && $GLOBALS['argc'] > 1) {
            return array_slice($GLOBALS['argv'], 1);
        }

        return [];
    }

    /**
     * Retrieves the command from the arguments array.
     *
     * @throws InvalidArgumentException If the command line arguments are invalid.
     * 
     * @return string|null The command or null if not found.
     */
    public static function command()
    {
        $args = self::arguments();

        $command = null;

        foreach ($args as $key => $value) {
            if (self::isOption($value) || self::isFlag($value)) {
                continue;
            }

            if ($key > 0) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid command position [%s]. Please check your arguments and try again.", $value
                ));
            }

            $command = $value;
        }

        return $command;
    }

    /**
     * Retrieves the command line options passed as arguments and returns them as an associative array.
     *
     * @throws InvalidArgumentException If the command line arguments are invalid.
     * 
     * @return array The command line options passed as arguments.
     */
    public static function options()
    {
        $args = self::arguments();

        if (!isset($args[0])) {
            return [];
        }
        
        $options = [];

        foreach ($args as $arg) {
            if (!self::isOption($arg)) {
                continue;
            }
            
            $arg = substr($arg, 2);

            $option = explode('=', $arg, 2);

            if (empty($option[0])) {
                throw new InvalidArgumentException(
                    "Invalid option format. Please check your arguments and try again."
                );
            }

            $name = $option[0];
            $value = true;
            
            if (!empty($option[1])) {
                $value = $option[1];
            }

            $options[$name] = $value;
        }

        return $options;
    }

    /**
     * Generate the function comment for the given function body.
     *
     * @return array The array of flags extracted from the command line arguments.
     */
    public static function flags()
    {
        $args = self::arguments();

        if (!isset($args[0])) {
            return [];
        }
        
        $flags = [];

        foreach ($args as $arg) {
            if (!self::isFlag($arg)) {
                continue;
            }

            $flags[] = substr($arg, 1);
        }

        return $flags;
    }
}