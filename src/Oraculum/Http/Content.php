<?php

namespace Oraculum\Http;

use Oraculum\Support\Contracts\Emptyable;
use Oraculum\Support\Primitives\PrimitiveObject;

class Content extends PrimitiveObject implements Emptyable
{
    /**
     * @var Header $header The HTTP header of the content.
     */
    private $header;

    /**
     * @var string $data The data of the content.
     */
    private $data;

    /**
     * Creates a new instance of the class.
     *
     * @param Header $header The HTTP header of the content.
     * @param string $data   The data of the content.
     *
     * @return void
     */
    public function __construct($header, $data = '')
    {
        $this->header = $header;
        $this->data   = $data;
    }

    /**
     * Creates an empty instance of the class.
     * 
     * @return self The empty instance.
     */
    public static function empty()
    {
        return new self(Header::empty());
    }

    /**
     * Gets the HTTP header of the content.
     *
     * @return Header Returns the HTTP header of the content.
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * Gets the data of the content.
     *
     * @return string Returns the data of the content.
     */
    public function getData()
    {
        return $this->data;
    }
}
