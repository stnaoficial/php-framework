<?php

namespace Miscellaneous\Html\Components;

use Oraculum\Html\Abstracts\Component;
use Oraculum\Html\Element;
use Oraculum\Html\Parser;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Attributes\Override;

final class FormField extends Component
{
    /**
     * Defines the HTML component composition.
     * 
     * @return string Returns the HTML component composition.
     */
    #[Overridee]
    protected function compose()
    {
        $label = ArraySupport::restore($this->attributes, 'label');

        return Parser::new()->parseChildren([
            $label? Element::new('label', ['for' => $this->getAttribute('id')], [
                $label
            ]) : null,
            Element::new('input', $this->attributes),
        ]);
    }
}