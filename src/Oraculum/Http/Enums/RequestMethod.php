<?php

namespace Oraculum\Http\Enums;

use Oraculum\Support\Traits\Enumerable;

enum RequestMethod: string
{
    use Enumerable;

    case GET     = 'GET';
    case POST    = 'POST';
    case PUT     = 'PUT';
    case PATCH   = 'PATCH';
    case DELETE  = 'DELETE';
    case OPTIONS = 'OPTIONS';
    case HEAD    = 'HEAD';
    case INFO    = 'INFO';
}