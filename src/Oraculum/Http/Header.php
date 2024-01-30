<?php

namespace Oraculum\Http;

use Oraculum\Contracts\Arrayable;
use Oraculum\Contracts\FromCapture;
use Oraculum\Contracts\Emptyable;
use InvalidArgumentException;
use Oraculum\Contracts\FromArray;
use Oraculum\Http\Enums\StatusCode;
use Oraculum\Http\Support\Header as HeaderSupport;
use Oraculum\Http\Support\Server as ServerSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

class Header extends PrimitiveObject implements Emptyable, FromArray, FromCapture, Arrayable
{
    use GloballyAvailable;

    /**
     * @var array The HTTP headers.
     */
    private $headers;

    /**
     * @var StatusCode The HTTP status code.
     */
    private $code;

    /**
     * Creates a new instance of the class.
     * 
     * @param array            $headers The HTTP headers to set.
     * @param StatusCode|int   $code    The HTTP status code.
     * 
     * @throws InvalidArgumentException If the HTTP status code is invalid.
     * 
     * @return void
     */
    public function __construct($headers = [], $code = StatusCode::OK)
    {
        $this->headers = $headers;
        $this->code($code);
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
     * Creates a new instance from an array.
     * 
     * @param array $array The array to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromArray($array)
    {
        return new self($array);
    }

    /**
     * Creates a new instance from an capture.
     * 
     * @throws InvalidArgumentException If some argument during the capture is invalid.
     * 
     * @return self The new instance.
     */
    public static function fromCapture()
    {
        return self::fromArray(HeaderSupport::current());
    }

    /**
     * Gets a array representation of the object.
     * 
     * @return array Returns the `array` representation of the object.
     */
    public function toArray()
    {
        return $this->headers;
    }

    /**
     * Gets or sets the header HTTP status code.
     * 
     * @param StatusCode|int|null $code The HTTP status code.
     * 
     * @throws InvalidArgumentException If the HTTP status code is invalid.
     * 
     * @return StatusCode Returns the HTTP status code.
     */
    public function code($code = null)
    {
        if (null === $code) {
            return $this->code;
        }

        if ($code instanceof StatusCode) {
            return $this->code = $code;
        }

        if (null === $code = StatusCode::tryFrom($code)) {
            throw new InvalidArgumentException(sprintf(
                "Invalid HTTP status code %s.", $code
            ));
        }

        return $this->code = $code;
    }

    /**
     * Check if a header exists.
     * 
     * @param string $name The name of the header.
     * 
     * @return bool Returns `true` if the header is set, otherwise `false`.
     */
    public function has($name)
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Gets an header.
     * 
     * @param string $name The name of the header.
     * 
     * @return string|null Returns the header or `null` if the header is not set.
     */
    public function get($name)
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Sets a header.
     * 
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     * 
     * @return void
     */
    public function set($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Remove previously set headers.
     * 
     * @param string|null $name The name of the header to remove or `null` to remove all.
     * 
     * @return void
     */
    public function undo($name = null)
    {
        header_remove($name);
    }

    /**
     * Send all headers.
     * 
     * @param bool $replace If `true` the header will be replaced, otherwise it will be added.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function send($replace = true)
    {
        $this->undo();

        $this->headers[] = ServerSupport::protocol() . ' ' . $this->code->value . ' ' . $this->code->message();

        http_response_code($this->code->value);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, $replace, $this->code->value);
        }

        return true;
    }
}