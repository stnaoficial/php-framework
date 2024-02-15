<?php

namespace Oraculum\Html\Support;

final class Html
{
    /**
     * Minifies the given HTML source code.
     * 
     * @param string $source The HTML source code to minify.
     * 
     * @return string The minified HTML source code.
     */
    public static function minify($source)
    {
        $replacements = [
            '/(\n|^)(\x20+|\t)/'      => "\n",
            '/(\n|^)\/\/(.*?)(\n|$)/' => "\n",
            '/\n/'                    => " ",
            '/\<\!--.*?-->/'          => "",
            '/(\x20+|\t)/'            => " ",   // Multispace (Without \n)
            '/\>\s+\</'               => "><",  // Whitespaces between tags
            '/(\"|\')\s+\>/'          => "$1>", // Whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'           => "=$1", // Whitespaces between = "'
        ];

        // Gets the replacement search and replace values for serialization.
        $search  = array_keys($replacements);
        $replace = array_values($replacements);

        return preg_replace($search, $replace, $source);
    }
}