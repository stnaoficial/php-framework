<?php

namespace Oraculum\Html;

use Oraculum\Html\Contracts\Htmlable;
use Oraculum\Html\Enums\VoidElement;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Parser extends PrimitiveObject
{
    /**
     * Parses the given attributes and returns the HTML representation of it.
     * 
     * This method is used internally by the `parseTag` method.
     * 
     * @param array $attributes The attributes to parse.
     * 
     * @return string The HTML representation of the attributes.
     */
    public function parseAttributes($attributes)
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            if (is_bool($value)) {
                $html .= $value? " {$key}" : '';
            
            } else {
                $html .= " {$key}=\"{$value}\"";
            }
        }

        return $html;
    }

    /**
     * Parses the given children and returns the HTML representation of it.
     * 
     * This method is used internally by the `parseTag` method.
     * 
     * @param array $children The children to parse.
     * 
     * @return string The HTML representation of the children.
     */
    public function parseChildren($children)
    {
        $html = '';

        foreach ($children as $child) {
            if ($child instanceof Htmlable) {
                $child = $child->toHtml();
            }

            $html .= $child;
        }

        return $html;
    }

    /**
     * Parses the given element and returns the HTML representation of it.
     * 
     * @param string $name       The name of the element.
     * @param array  $attributes The attributes of the element.
     * @param array  $children   The children of the element.
     * 
     * @return string The HTML representation of the element.
     */
    public function parseElement($name = 'div', $attributes = [], $children = [])
    {
        $element = "<{$name}";

        $element .= $this->parseAttributes($attributes);

        if (VoidElement::tryFrom($name)) {
            return $element .= " />";
        }

        $element .= '>';

        $element .= $this->parseChildren($children);

        return $element .= "</{$name}>";
    }
}