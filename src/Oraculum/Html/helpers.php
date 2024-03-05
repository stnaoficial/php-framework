<?php declare(strict_types=1);

if (!function_exists('element')) {
    /**
     * Creates a new HTML element.
     * 
     * @param string $name       The name of the element.
     * @param array  $attributes The attributes of the element.
     * @param array  $children   The children of the element.
     * 
     * @return Oraculum\Html\Element Returns a new HTML element.
     */
    function element($name = 'div', $attributes = [], $children = [])
    {
        return new \Oraculum\Html\Element($name, $attributes, $children);
    }
}