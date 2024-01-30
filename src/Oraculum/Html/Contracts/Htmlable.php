<?php

namespace Oraculum\Html\Contracts;

interface Htmlable
{
    /**
     * Gets the HTML representation of the object.
     * 
     * @return mixed The HTML representation of the object.
     */
    public function toHtml();
}