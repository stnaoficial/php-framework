<?php

namespace Miscellaneous\Http;

use Oraculum\Contracts\FromMedia;
use Oraculum\Contracts\Media;
use Oraculum\Http\Response as BaseResponse;

final class Response extends BaseResponse implements FromMedia
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
        $content = Content::fromMedia($media);

        return new self($content);
    }
}