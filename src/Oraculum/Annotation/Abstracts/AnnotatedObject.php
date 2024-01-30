<?php

namespace Oraculum\Annotation\Abstracts;

use Oraculum\Annotation\Exceptions\AnnotationException;
use Oraculum\Annotation\Support\Annotation as AnnotationSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use ReflectionException;

abstract class AnnotatedObject extends PrimitiveObject
{
    /**
     * Get the class annotations
     * 
     * @return array An array of annotations
     */
    protected function getClassAnnotations()
    {
        return AnnotationSupport::getClassAnnotations($this);
    }

    /**
     * Check if the class has an annotation.
     * 
     * @param string $name The name of the annotation.
     * 
     * @return bool Returns `true` if the class has the annotation, otherwise `false`.
     */
    protected function hasClassAnnotation($name)
    {
        return AnnotationSupport::hasClassAnnotation($this, $name);
    }

    /**
     * Get a class annotation.
     * 
     * @param string $name The name of the annotation.
     * 
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The annotation.
     */
    protected function getClassAnnotation($name)
    {
        return AnnotationSupport::getClassAnnotation($this, $name);
    }

    /**
     * Get the property annotations.
     * 
     * @param string $property The name of the property.
     * 
     * @throws ReflectionException If the property does not exist.
     * 
     * @return array An array of annotations
     */
    protected function getPropertyAnnotations($property)
    {
        return AnnotationSupport::getPropertyAnnotations($this, $property);
    }

    /**
     * Check if the property has an annotation.
     * 
     * @param string $property The name of the property.
     * @param string $name     The name of the annotation.
     * 
     * @return bool Returns `true` if the property has the annotation, otherwise `false`.
     */
    protected function hasPropertyAnnotation($property, $name)
    {
        return AnnotationSupport::hasPropertyAnnotation($this, $property, $name);
    }

    /**
     * Get a property annotation.
     * 
     * @param string $property The name of the property.
     * @param string $name     The name of the annotation.
     * 
     * @throws ReflectionException If the property does not exist.
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The annotation.
     */
    protected function getPropertyAnnotation($property, $name)
    {
        return AnnotationSupport::getPropertyAnnotation($this, $property, $name);
    }

    /**
     * Get the method annotations.
     * 
     * @param string $method The name of the method.
     * 
     * @throws ReflectionException If the method does not exist.
     * 
     * @return array An array of annotations
     */
    protected function getMethodAnnotations($method)
    {
        return AnnotationSupport::getMethodAnnotations($this, $method);
    }

    /**
     * Check if the method has an annotation.
     * 
     * @param string $method The name of the method.
     * @param string $name   The name of the annotation.
     * 
     * @return bool Returns `true` if the method has the annotation, otherwise `false`.
     */
    protected function hasMethodAnnotation($method, $name)
    {
        return AnnotationSupport::hasMethodAnnotation($this, $method, $name);
    }

    /**
     * Get a method annotation.
     * 
     * @param string $method The name of the method.
     * @param string $name   The name of the annotation.
     * 
     * @throws ReflectionException If the method does not exist.
     * @throws AnnotationException If the annotation does not exist.
     * 
     * @return string The annotation.
     */
    protected function getMethodAnnotation($method, $name)
    {
        return AnnotationSupport::getMethodAnnotation($this, $method, $name);
    }
}