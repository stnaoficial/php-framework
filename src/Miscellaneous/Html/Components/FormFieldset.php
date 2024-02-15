<?php

namespace Miscellaneous\Html\Components;

use Oraculum\Html\Abstracts\Component;
use Oraculum\Html\Element;
use Oraculum\Html\Parser;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Attributes\Override;

final class FormFieldset extends Component
{
    /**
     * Defines the HTML component composition.
     * 
     * @return string Returns the HTML component composition.
     */
    #[Override]
    protected function compose()
    {   
        $legend = ArraySupport::restore($this->attributes, 'legend');

        return Element::new('fieldset', $this->attributes, [
            $legend? Element::new('legend', [], [
                $legend
            ]) : null,
            Parser::new()->parseChildren($this->children),
        ]);
    }
}