<?php

namespace Miscellaneous\Http;

use Miscellaneous\Support\Contracts\FromAny;
use Oraculum\Http\Response as BaseResponse;

final class Response extends BaseResponse implements FromAny
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
        $content = Content::fromAny($data);

        return new self($content);
    }
}