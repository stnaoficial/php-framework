<?php

namespace Miscellaneous\Html\Components;

use Oraculum\Html\Abstracts\Component;
use Oraculum\Html\Element;
use Oraculum\Support\Attributes\Override;

final class Form extends Component
{
    /**
     * Defines the HTML component composition.
     * 
     * @return string Returns the HTML component composition.
     */
    #[Override]
    protected function compose()
    {
        return Element::new('form', $this->attributes, $this->children);
    }
}