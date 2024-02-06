<?php

namespace Miscellaneous\Http;

use Oraculum\Contracts\FromMedia;
use Oraculum\Contracts\Media;
use Oraculum\FileSystem\File;
use Oraculum\Http\Content as BaseContent;

final class Content extends BaseContent implements FromMedia
{
    /**
     * Creates a new instance from an media.
     * 
     * @param Media $media The media to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromMedia($media)
    {
        if ($media instanceof File) {
            return new self(Header::fromFile($media), $media->read() ?: '');
        }

        return new self(Header::fromMedia($media), $media);
    }
}