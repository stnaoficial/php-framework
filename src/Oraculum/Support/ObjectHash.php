<?php

namespace Oraculum\Support;

final class ObjectHash
{
    /**
     * Get the hash of an object.
     * 
     * @param object $object The object to hash.
     * 
     * @return string The hash of the object.
     */
    public static function hash(object $object)
    {
        return spl_object_hash($object);
    }
}