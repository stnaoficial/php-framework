<?php

namespace Oraculum\Support\Contracts;

use Stringable as MagicStringable;

interface Stringable extends MagicStringable
{
    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string;

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString();
}