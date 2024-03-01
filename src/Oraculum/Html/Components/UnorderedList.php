<?php

namespace Oraculum\Html\Components;

use Oraculum\Html\Abstracts\Component;
use Oraculum\Html\Element;
use Oraculum\Support\Attributes\Override;

final class UnorderedList extends Component
{
    /**
     * Defines the HTML component composition.
     * 
     * @return string Returns the HTML component composition.
     */
    #[Override]
    protected function compose()
    {
        return Element::new('ul', $this->attributes, array_map(function($child) {
            return Element::new('li', children: [$child]);
        }, $this->children));
    }
}