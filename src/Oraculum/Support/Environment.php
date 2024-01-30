<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Environment
{
    use NonInstantiable;

    /**
     * Check if the current environment is running in CLI mode.
     *
     * @return bool Returns `true` if running in CLI mode, `false` otherwise.
     */
    public static function isCli()
    {
        if ($mode = php_sapi_name()) {
            return $mode === 'cli';
        }

        return false;
    }
}
