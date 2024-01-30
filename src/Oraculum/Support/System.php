<?php

namespace Oraculum\Support;

final class System
{
    /**
     * Gets the input from the standard input.
     * 
     * @return string The input from the standard input.
     */
    public static function input()
    {
        $input = fopen("php://stdin","r");

        $line = null;

        while (is_null($line)) {
            $line = fgets($input);
        }
        
        fclose($input);

        $line = trim($line);

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
        $output = fopen("php://stdout","w");

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
        $output = fopen("php://stderr","w");

        fwrite($output, $data);

        fclose($output);
    }
}