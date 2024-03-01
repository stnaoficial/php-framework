<?php

namespace Oraculum\Http;

use Oraculum\Support\Contracts\Emptyable;
use Oraculum\Http\Contracts\Communicable;
use Oraculum\Support\Primitives\PrimitiveObject;

class Response extends PrimitiveObject implements Emptyable, Communicable
{
    /**
     * @var Content $content The content of the response.
     */
    private $content;

    /**
     * Creates a new instance of the class.
     * 
     * @param Content|string|null $content The content of the response.
     * 
     * @return void
     */
    public function __construct($content = null)
    {
        if (is_null($content)) {
            $content = Content::empty();
        }

        if (is_string($content)) {
            $content = new Content(Header::empty(), $content);
        }

        $this->content = $content;
    }

    /**
     * Creates an empty instance of the class.
     * 
     * @return self The empty instance.
     */
    public static function empty()
    {
        return new self;
    }

    /**
     * Gets the content of the response.
     * 
     * @return Content The content of the response.
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Sends the communication.
     * 
     * @return void
     */
    public function send()
    {
        $this->content->header()->send();

        echo $this->content->getData();
    }
}