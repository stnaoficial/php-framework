<?php

namespace Oraculum\Contracts;

interface FromMedia
{
    /**
     * Creates a new instance from an media.
     * 
     * @param Media $media The media to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromMedia($media);
}