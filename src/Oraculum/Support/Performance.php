<?php

namespace Oraculum\Support;

final class Performance
{
    /**
     * The start micro time.
     * 
     * @var float
     */
    private static $start = 0;

    /**
     * Start the performance.
     * 
     * @return void
     */
    public static function start()
    {
        self::$start = microtime(true);
    }

    /**
     * End the performance.
     * 
     * @return float The end time.
     */
    public static function end()
    {
        return microtime(true) - self::$start;
    }

    /**
     * Flush the performance.
     * 
     * @return void
     */
    public static function flush()
    {
        self::$start = 0;
    }
}