<?php

namespace Miscellaneous\Http;

use Miscellaneous\Support\Contracts\FromAny;
use Oraculum\FileSystem\File;
use Oraculum\Http\Content as BaseContent;

final class Content extends BaseContent implements FromAny
{
    /**
     * Creates a new instance from any data type.
     * 
     * @param mixed $data The data to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromAny($data)
    {
        if ($data instanceof File) {
            return new self(Header::fromFile($data), $data->read() ?: '');
        }

        return new self(Header::fromAny($data), $data);
    }
}