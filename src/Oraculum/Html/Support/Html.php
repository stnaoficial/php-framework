<?php

namespace Oraculum\Html\Support;

use Oraculum\Html\Element;

if (!ini_get('highlight')) {
    ini_set('highlight.default', '#404040');
    ini_set('highlight.html', '#404040');
    ini_set('highlight.comment', '#15803d');
    ini_set('highlight.keyword', '#2563eb');
    ini_set('highlight.string', '#b91c1c');
}

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
        $search = [
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/',             // Multispace (Without \n)
            '/\>\s+\</',                // Whitespaces between tags
            '/(\"|\')\s+\>/',           // Whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'             // Whitespaces between = "'
        ];

        $replace = [
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1"
        ];

        return preg_replace($search, $replace, $source);
    }

    /**
     * Highlights the given text with the given options.
     * 
     * @param string                   $text    The text to highlight.
     * @param array{show-lines: bool}  $options An array of options to pass.
     * 
     * @return string The HTML highlighted text.
     */
    public static function highlight($text, $options = [])
    {
        $text = highlight_string($text, true);

        if (!isset($options["show-lines"]) || !$options["show-lines"]) {
            return $text;
        }

        $lines = explode(new Element("br"), $text);

        foreach (array_keys($lines) as $line) {
            $lines[$line] = $line + 1;
        }

        $lines = implode(new Element("br"), $lines);

        $html = new Element("div", ["style" => "display: flex; flex-direction: row; gap: 1rem;"], [
            new Element("div", ["style" => "text-align: right;"], [
                $lines
            ]),
            new Element("div", children: [
                $text
            ])
        ]);

        return $html;
    }
}