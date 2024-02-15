<?php

namespace Oraculum\Annotation\Support;

use Oraculum\Annotation\Exceptions\AnnotationException;
use Oraculum\Support\Traits\NonInstantiable;
use ReflectionClass;
use ReflectionException;

final class Annotation
{
    use NonInstantiable;

    /**
     * The regex pattern to parse doc comments.
     */
    private const DOC_COMMENT_PATTERN = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";

    /**
     * Parse the doc comment into an associative array.
     * 
     * @param string $comment The doc comment to parse.
     * 
     * @return array<string> An array of annotations.
     */
    private static function parseDocComment($comment)
    {
        preg_match_all(self::DOC_COMMENT_PATTERN, $comment, $matches, PREG_PATTERN_ORDER);

        if (!is_array($matches) || !is_array($matches[1])) {
            return [];
        }

        $annotations = [];

        foreach ($matches[1] as $match) {
            // Gets the first token of the annotation as their name.
            // e.g. "@name ..." => "@name"
            list($name) = explode(' ', $match);

            // Removes the name from the match.
            // e.g. "@name ..." => "..."
            $match = trim(substr($match, strlen($name)));

            // Removes the @ symbol from the name.
            // e.g. "@name" => "name"
            $name = substr($name, 1);

            $annotations[$name] = $match;
        }
    
        return $annotations;
    }

    /**
     * Find an annotation.
     * 
     * @param array<string> $annotations A array annotations.
     * 
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The annotation.
     */
    private static function findOrFail($annotations, $name)
    {
        if (!isset($annotations[$name])) {
            throw new AnnotationException(sprintf(
                "Annotation [%s] does not exist.", $name
            ));
        }

        return $annotations[$name];
    }

    /**
     * Get the class annotations.
     * 
     * @param object|string $class The class to get the annotations from.
     * 
     * @throws ReflectionException If the class does not exist.
     * 
     * @return array An associative array of the class annotations.
     */
    public static function getClassAnnotations($class)
    {
        $reflection = new ReflectionClass($class);

        return self::parseDocComment(
            $reflection->getDocComment() ?: ''
        );
    }

    /**
     * Check if the class has an annotation.
     * 
     * @param object|string $class  The class to get the annotations from.
     * @param string        $name   The name of the annotation.
     * 
     * @throws ReflectionException If the class does not exist.
     * 
     * @return bool Returns `true` if the class has the annotation, `false` otherwise.
     */
    public static function hasClassAnnotation($class, $name)
    {
        $annotations = self::getClassAnnotations($class);

        return isset($annotations[$name]);
    }

    /**
     * Get the class annotation.
     * 
     * @param object|string $class The class to get the annotation from.
     * @param string        $name  The name of the annotation.
     * 
     * @throws ReflectionException If the class does not exist.
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The value of the annotation.
     */
    public static function getClassAnnotation($class, $name)
    {
        $annotations = self::getClassAnnotations($class);

        return self::findOrFail($annotations, $name);
    }

    /**
     * Get the property annotations.
     * 
     * @param object|string $class    The class to get the annotations from.
     * @param string        $property The property to get the annotations from.
     * 
     * @throws ReflectionException If the class or property does not exist.
     * 
     * @return array An associative array of the property annotations.
     */
    public static function getPropertyAnnotations($class, $property)
    {
        $reflection = new ReflectionClass($class);

        return self::parseDocComment(
            $reflection->getProperty($property)->getDocComment() ?: ''
        );
    }

    /**
     * Check if the property has an annotation.
     * 
     * @param object|string $class    The class to get the annotations from.
     * @param string        $property The property to get the annotations from.
     * @param string        $name     The name of the annotation.
     * 
     * @throws ReflectionException If the class or property does not exist.
     * 
     * @return bool Returns `true` if the property has the annotation, `false` otherwise.
     */
    public static function hasPropertyAnnotation($class, $property, $name)
    {
        $annotations = self::getPropertyAnnotations($class, $property);

        return isset($annotations[$name]);
    }

    /**
     * Get the property annotation.
     * 
     * @param object|string $class    The class to get the annotation from.
     * @param string        $property The property to get the annotation from.
     * @param string        $name     The name of the annotation.
     * 
     * @throws ReflectionException If the class or property does not exist.
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The value of the annotation.
     */
    public static function getPropertyAnnotation($class, $property, $name)
    {
        $annotations = self::getPropertyAnnotations($class, $property);

        return self::findOrFail($annotations, $name);
    }

    /**
     * Get the method annotations.
     * 
     * @param object|string $class  The class to get the annotations from.
     * @param string        $method The method to get the annotations from.
     * 
     * @throws ReflectionException If the class or method does not exist.
     * 
     * @return array An associative array of the method annotations.
     */
    public static function getMethodAnnotations($class, $method)
    {
        $reflection = new ReflectionClass($class);

        return self::parseDocComment(
            $reflection->getMethod($method)->getDocComment() ?: ''
        );
    }

    /**
     * Check if the method has an annotation.
     * 
     * @param object|string $class  The class to get the annotations from.
     * @param string        $method The method to get the annotations from.
     * @param string        $name   The name of the annotation.
     * 
     * @throws ReflectionException If the class or method does not exist.
     * 
     * @return bool Returns `true` if the method has the annotation, `false` otherwise.
     */
    public static function hasMethodAnnotation($class, $method, $name)
    {
        $annotations = self::getMethodAnnotations($class, $method);

        return isset($annotations[$name]);
    }

    /**
     * Get the method annotation.
     * 
     * @param object|string $class  The class to get the annotation from.
     * @param string        $method The method to get the annotation from.
     * @param string        $name   The name of the annotation.
     * 
     * @throws ReflectionException If the class or method does not exist.
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The value of the annotation.
     */
    public static function getMethodAnnotation($class, $method, $name)
    {
        $annotations = self::getMethodAnnotations($class, $method);

        return self::findOrFail($annotations, $name);
    }
}