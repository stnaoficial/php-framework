<?php

namespace Oraculum\Http;

use Oraculum\Support\Contracts\Arrayable;
use Oraculum\Support\Contracts\FromCapture;
use Oraculum\Support\Contracts\Emptyable;
use InvalidArgumentException;
use Oraculum\Support\Contracts\FromArray;
use Oraculum\Support\Contracts\Stringable;
use Oraculum\Http\Enums\StatusCode;
use Oraculum\Http\Support\Header as HeaderSupport;
use Oraculum\Http\Support\Server as ServerSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

class Header extends PrimitiveObject implements Emptyable, FromArray, FromCapture, Arrayable, Stringable
{
    use GloballyAvailable;

    /**
     * @var StatusCode $code The HTTP status code.
     */
    private $code;

    /**
     * @var array $headers The HTTP headers.
     */
    private $headers = [];

    /**
     * Creates a new instance of the class.
     * 
     * @param StatusCode|int   $code    The HTTP status code.
     * @param array            $headers The HTTP headers to set.
     * 
     * @throws InvalidArgumentException If the HTTP status code is invalid.
     * 
     * @return void
     */
    public function __construct($code = StatusCode::OK, $headers = [])
    {
        $this->code($code);

        $this->headers = array_merge($this->headers, $headers);
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
        return new self(headers: $array);
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
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        // Sets the HTTP status code header as the first header.
        // This is required by the HTTP specification.
        $header = $this->codeHeader() . "\r\n";

        // Concatenates all headers into a single string.
        foreach ($this->headers as $name => $value) {
            $header .= (is_string($name)? sprintf("%s: %s", $name, $value) : $value) . "\r\n";
        }

        // Puts one more empty line at the end.
        // This is also required by the HTTP specification.
        $header .= "\r\n";

        return $header;
    }

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString()
    {
        return $this->__toString();
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
        // Just returns the current HTTP status code if no argument is passed.
        // Thats the default behavior.
        if (is_null($code)) {
            return $this->code;
        }

        // Sets the HTTP status code as it is passed if it already implements an
        // `StatusCode`.
        if ($code instanceof StatusCode) {
            $this->code = $code;
        }

        // Throws an exception if the HTTP status code is invalid and is not an
        // implementation of `StatusCode`.
        else {
            if (null === $this->code = StatusCode::tryFrom($code)) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid HTTP status code %s.", $code
                ));
            }
        }

        return $this->code;
    }

    /**
     * Gets the HTTP status code header.
     * 
     * @return string Returns the HTTP status code header.
     */
    private function codeHeader()
    {
        return sprintf("%s %s %s", ServerSupport::version(), $this->code->value, $this->code->message());
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
     * Puts a header.
     * 
     * @param string $value The value of the header.
     * 
     * @return void
     */
    public function put($value)
    {
        $this->headers[] = $value;
    }

    /**
     * Send all headers.
     * 
     * @param bool $replace If `true` the header will be replaced, otherwise it will be added.
     * 
     * @return void
     */
    public function send($replace = true)
    {
        // Removes all existing headers.
        // Prevents the old headers from being sent multiple times.
        header_remove();

        // Sets the HTTP response code.
        http_response_code($this->code->value);

        // Sends the HTTP status code header.
        header($this->codeHeader());

        foreach ($this->headers as $name => $value) {
            header(is_string($name)? sprintf("%s: %s", $name, $value) : $value, $replace, $this->code->value);
        }
    }
}