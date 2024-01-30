<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Ob
{
    use NonInstantiable;

    /**
     * Opens the output buffering.
     * 
     * @return void
     */
    public static function open()
    {
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        ob_start();
    }

    /**
     * Closes the output buffering and return their contents.
     * 
     * @return string The contents of the output buffer.
     */
    public static function close()
    {
        if (false !== $contents = ob_get_contents()) {
            ob_end_clean();
        
        } else {
            $contents = '';
        }

        return $contents;
    }

    /**
     * Returns the contents of the output buffer.
     * 
     * @return string The contents of the output buffer.
     */
    public static function peek()
    {
        if (false !== $contents = ob_get_contents()) {
            return $contents;
        };

        return '';
    }

    /**
     * Resumes the output buffering.
     * 
     * @return string The contents of the resumed output buffer.
     */
    public static function resume()
    {
        $contents = self::close();

        self::open();

        return $contents;
    }
}