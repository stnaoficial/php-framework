<?php

namespace Oraculum\Html\Macros;

use Oraculum\Html\Element;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Traits\Macroable;
use Oraculum\Support\Traits\NonInstantiable;

final class Html
{
    use NonInstantiable, Macroable;

    /**
     * Creates a new HTML element.
     * 
     * @param string $name       The name of the element.
     * @param array  $attributes The attributes of the element.
     * @param array  $children   The children of the element.
     * 
     * @return Element Returns a new HTML element.
     */
    public static function element($name, $attributes = [], $children = [])
    {
        return new Element($name, $attributes, $children);
    }

    /**
     * Creates a new HTML title.
     * 
     * @param array $children The children of the title.
     * 
     * @return Element Returns a new HTML title.
     */
    public static function title($children = [])
    {
        return self::element("title", children: $children);
    }

    /**
     * Creates a new HTML meta.
     * 
     * @param array $attributes The attributes of the meta.
     * 
     * @return Element Returns a new HTML meta.
     */
    public static function meta($attributes = [])
    {
        return self::element("meta", $attributes);
    }

    /**
     * Creates a new HTML link.
     * 
     * @param array $attributes The attributes of the link.
     * 
     * @return Element Returns a new HTML link.
     */
    public static function link($attributes = [])
    {
        return self::element("link", $attributes);
    }

    /**
     * Creates a new HTML script.
     * 
     * @param array $attributes The attributes of the script.
     * @param array $children   The children of the script.
     * 
     * @return Element Returns a new HTML script.
     */
    public static function script($attributes = [], $children = [])
    {
        return self::element("script", $attributes, $children);
    }

    /**
     * Creates a new HTML style.
     * 
     * @param array $attributes The attributes of the style.
     * @param array $children   The children of the style.
     * 
     * @return Element Returns a new HTML style.
     */
    public static function style($attributes = [], $children = [])
    {
        return self::element("style", $attributes, $children);
    }

    /**
     * Creates a new HTML h1.
     * 
     * @param array $attributes The attributes of the h1.
     * @param array $children   The children of the h1.
     * 
     * @return Element Returns a new HTML h1.
     */
    public static function h1($attributes = [], $children = [])
    {
        return self::element("h1", $attributes, $children);
    }

    /**
     * Creates a new HTML h2.
     * 
     * @param array $attributes The attributes of the h2.
     * @param array $children   The children of the h2.
     * 
     * @return Element Returns a new HTML h2.
     */
    public static function h2($attributes = [], $children = [])
    {
        return self::element("h2", $attributes, $children);
    }

    /**
     * Creates a new HTML h3.
     * 
     * @param array $attributes The attributes of the h3.
     * @param array $children   The children of the h3.
     * 
     * @return Element Returns a new HTML h3.
     */
    public static function h3($attributes = [], $children = [])
    {
        return self::element("h3", $attributes, $children);
    }

    /**
     * Creates a new HTML h4.
     * 
     * @param array $attributes The attributes of the h4.
     * @param array $children   The children of the h4.
     * 
     * @return Element Returns a new HTML h4.
     */
    public static function h4($attributes = [], $children = [])
    {
        return self::element("h4", $attributes, $children);
    }

    /**
     * Creates a new HTML h5.
     * 
     * @param array $attributes The attributes of the h5.
     * @param array $children   The children of the h5.
     * 
     * @return Element Returns a new HTML h5.
     */
    public static function h5($attributes = [], $children = [])
    {
        return self::element("h5", $attributes, $children);
    }

    /**
     * Creates a new HTML h6.
     * 
     * @param array $attributes The attributes of the h6.
     * @param array $children   The children of the h6.
     * 
     * @return Element Returns a new HTML h6.
     */
    public static function h6($attributes = [], $children = [])
    {
        return self::element("h6", $attributes, $children);
    }

    /**
     * Creates a new HTML paragraph.
     * 
     * @param array $attributes The attributes of the paragraph.
     * 
     * @return Element Returns a new HTML paragraph.
     */
    public static function p($attributes = [], $children = [])
    {
        return self::element("p", $attributes, $children);
    }

    /**
     * Creates a new HTML span.
     * 
     * @param array $attributes The attributes of the span.
     * @param array $children   The children of the span.
     * 
     * @return Element Returns a new HTML span.
     */
    public static function span($attributes = [], $children = [])
    {
        return self::element("span", $attributes, $children);
    }

    /**
     * Creates a new HTML break line.
     * 
     * @param array $attributes The attributes of the break line.
     * 
     * @return Element Returns a new HTML break line.
     */
    public static function br($attributes = [])
    {
        return self::element("br", $attributes);
    }

    /**
     * Creates a new HTML header.
     * 
     * @param array $attributes The attributes of the header.
     * @param array $children   The children of the header.
     * 
     * @return Element Returns a new HTML header.
     */
    public static function header($attributes = [], $children = [])
    {
        return self::element("header", $attributes, $children);
    }

    /**
     * Creates a new HTML footer.
     * 
     * @param array $attributes The attributes of the footer.
     * @param array $children   The children of the footer.
     * 
     * @return Element Returns a new HTML footer.
     */
    public static function footer($attributes = [], $children = [])
    {
        return self::element("footer", $attributes, $children);
    }

    /**
     * Creates a new HTML navigation.
     * 
     * @param array $attributes The attributes of the navigation.
     * @param array $children   The children of the navigation.
     * 
     * @return Element Returns a new HTML navigation.
     */
    public static function nav($attributes = [], $children = [])
    {
        return self::element("nav", $attributes, $children);
    }

    /**
     * Creates a new HTML article.
     * 
     * @param array $attributes The attributes of the article.
     * @param array $children   The children of the article.
     * 
     * @return Element Returns a new HTML article.
     */
    public static function article($attributes = [], $children = [])
    {
        return self::element("article", $attributes, $children);
    }

    /**
     * Creates a new HTML section.
     * 
     * @param array $attributes The attributes of the section.
     * @param array $children   The children of the section.
     * 
     * @return Element Returns a new HTML section.
     */
    public static function section($attributes = [], $children = [])
    {
        return self::element("section", $attributes, $children);
    }

    /**
     * Creates a new HTML aside.
     * 
     * @param array $attributes The attributes of the aside.
     * @param array $children   The children of the aside.
     * 
     * @return Element Returns a new HTML aside.
     */
    public static function aside($attributes = [], $children = [])
    {
        return self::element("aside", $attributes, $children);
    }

    /**
     * Creates a new HTML main.
     * 
     * @param array $attributes The attributes of the main.
     * @param array $children   The children of the main.
     * 
     * @return Element Returns a new HTML main.
     */
    public static function main($attributes = [], $children = [])
    {
        return self::element("main", $attributes, $children);
    }

    /**
     * Creates a new HTML menu.
     * 
     * @param array $attributes The attributes of the menu.
     * @param array $children   The children of the menu.
     * 
     * @return Element Returns a new HTML menu.
     */
    public static function menu($attributes = [], $children = [])
    {
        return self::element("menu", $attributes, $children);
    }

    /**
     * Creates a new HTML menu item.
     * 
     * @param array $attributes The attributes of the menu item.
     * @param array $children   The children of the menu item.
     * 
     * @return Element Returns a new HTML menu item.
     */
    public static function menuItem($attributes = [], $children = [])
    {
        return self::element("menuitem", $attributes, $children);
    }

    /**
     * Creates a new HTML div.
     * 
     * @param array $attributes The attributes of the div.
     * @param array $children   The children of the div.
     * 
     * @return Element Returns a new HTML div.
     */
    public static function div($attributes = [], $children = [])
    {
        return self::element("div", $attributes, $children);
    }

    /**
     * Creates a new HTML form.
     * 
     * @param array $attributes The attributes of the form.
     * @param array $children   The children of the form.
     * 
     * @return Element Returns a new HTML form.
     */
    public static function form($attributes = [], $children = [])
    {
        return self::element("form", $attributes, $children);
    }

    /**
     * Creates a new HTML label.
     * 
     * @param array $attributes The attributes of the label.
     * @param array $children   The children of the label.
     * 
     * @return Element Returns a new HTML label.
     */
    public static function label($attributes = [], $children = [])
    {
        return self::element("label", $attributes, $children);
    }

    /**
     * Creates a new HTML input field.
     * 
     * @param array $attributes The attributes of the input field.
     * 
     * @return Element Returns a new HTML input field.
     */
    public static function input($attributes = [])
    {
        return self::element("input", $attributes);
    }

    /**
     * Creates a new HTML button.
     * 
     * @param array $attributes The attributes of the button.
     * @param array $children   The children of the button.
     * 
     * @return Element Returns a new HTML button.
     */
    public static function button($attributes = [], $children = [])
    {
        return self::element("button", $attributes, $children);
    }

    /**
     * Creates a new HTML list item.
     * 
     * @param array $attributes The attributes of the list item.
     * @param array $children   The children of the list item.
     * 
     * @return Element Returns a new HTML list item.
     */
    public static function li($attributes = [], $children = [])
    {
        return self::element('li', $attributes, $children);
    }

    /**
     * Creates a new HTML ordered list.
     * 
     * @param array $attributes The attributes of the ordered list.
     * @param array $children   The children of the ordered list.
     * 
     * @return Element Returns a new HTML ordered list.
     */
    public static function ol($attributes = [], $children = [])
    {
        return self::element('ol', $attributes, array_map(function($child) {
            return $child instanceof Element? $child : self::li(children: [$child]);
        }, $children));
    }

    /**
     * Creates a new HTML unordered list.
     * 
     * @param array $attributes The attributes of the unordered list.
     * @param array $children   The children of the unordered list.
     * 
     * @return Element Returns a new HTML unordered list.
     */
    public static function ul($attributes = [], $children = [])
    {
        return self::element('ul', $attributes, array_map(function($child) {
            return $child instanceof Element? $child : self::li(children: [$child]);
        }, $children));
    }

    /**
     * Creates a new HTML head.
     * 
     * @param array $children The children of the head.
     * 
     * @return Element Returns a new HTML head.
     */
    public static function head($children = [])
    {
        return self::element("head", children: $children);
    }

    /**
     * Creates a new HTML body.
     * 
     * @param array $attributes The attributes of the body.
     * @param array $children   The children of the body.
     * 
     * @return Element Returns a new HTML body.
     */
    public static function body($attributes = [], $children = [])
    {
        return self::element("body", $attributes, $children);
    }

    /**
     * Creates a new HTML document.
     * 
     * @param array $attributes The attributes of the document.
     * 
     * @return string Returns a new HTML document encoded as a string.
     */
    public static function document($attributes = [])
    {
        $language = ArraySupport::restore($attributes, "language");

        return "<!DOCTYPE html>" . self::element("html", ["lang" => $language ?? "en"], [
            $attributes["head"] ?? self::head(),
            $attributes["body"] ?? self::body(),
        ]);
    }
}