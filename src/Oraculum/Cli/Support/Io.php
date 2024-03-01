<?php

namespace Oraculum\Cli\Support;

final class Io
{
    /**
     * Strictly converts the given value to the expected type.
     * 
     * @param string $value The value to be converted.
     * 
     * @return mixed The converted value.
     */
    public static function strict($value)
    {
        switch (trim($value)) {
            case in_array(strtoupper($value), ['Y', 'YES', 'TRUE'], true):
                return true;
            case in_array(strtoupper($value), ['N', 'NO', 'FALSE'], true):
                return false;
            case is_numeric($value):
                return is_float($value + 0)? floatval($value) : intval($value);
            case is_string($value):
                return $value;
        }
    }

    /**
     * Gets the input from the standard input.
     * 
     * @param bool $strict If the input needs to be strictly converted to its respective type.
     * 
     * @return mixed The input from the standard input.
     */
    public static function input($strict = false)
    {
        $input = fopen("php://stdin", 'r');

        $line = null;

        while (is_null($line)) {
            $line = fgets($input);
        }

        fclose($input);

        $line = trim($line);

        if ($strict) {
            return self::strict($line);
        }

        return $line;
    }

    /**
     * Outputs a data to the standard output.
     * 
     * @param mixed $data The data to be output.
     * 
     * @return void
     */
    public static function output($data)
    {
        $output = fopen("php://stdout", 'w');

        fwrite($output, $data);

        fclose($output);
    }

    /**
     * Outputs a data to the standard error.
     * 
     * @param mixed $data The data to be output.
     * 
     * @return void
     */
    public static function error($data)
    {
        $output = fopen("php://stderr", 'w');

        fwrite($output, $data);

        fclose($output);
    }
}