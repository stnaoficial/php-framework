<?php

namespace Oraculum\Http;

use Oraculum\Contracts\FromCapture;
use Oraculum\Http\Contracts\Communicable;
use Oraculum\Http\Support\Request as RequestSupport;
use Oraculum\Support\Primitives\PrimitiveObject;

/**
 * @template T of self
 */
final class Request extends PrimitiveObject implements FromCapture, Communicable
{
    /**
     * @var string The request method.
     */
    private $method;

    /**
     * @var Uri The request uri.
     */
    private $uri;

    /**
     * @var array The request parameters.
     */
    private $params;

    /**
     * @var array The request cookies.
     */
    private $cookies;

    /**
     * @var string The request body.
     */
    private $body;

    /**
     * Creates a new instance of the class.
     * 
     * @param string     $method     The request method.
     * @param string     $uri        The request uri.
     * @param array      $parameters The request parameters.
     * @param array      $cookies    The request cookies.
     * @param string     $body       The request body.
     * 
     * @return void
     */
    public function __construct($method, $uri, $parameters = [], $cookies = [], $body = '')
    {
        $this->method  = $method;
        $this->uri     = new Uri($uri);
        $this->params  = $parameters;
        $this->cookies = $cookies;
        $this->body    = $body;
    }

    /**
     * Creates a new instance from an capture.
     * 
     * @return T The new instance.
     */
    public static function fromCapture()
    {
        return new self(
            RequestSupport::method(),
            RequestSupport::uri(),
            RequestSupport::parameters(),
            RequestSupport::cookies(),
            RequestSupport::body()
        );
    }

    /**
     * Gets the request method.
     * 
     * @return string The request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Checks if the request method is one of the given methods.
     * 
     * @param string $methods One or more methods.
     * 
     * @return bool Returns `true` if the method is one of the given methods, `false` otherwise.
     */
    public function isMethod(...$methods)
    {
        return in_array($this->method, $methods);
    }

    /**
     * Gets the URI of the request.
     * 
     * @return Uri Returns the URI of the request.
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Get all parameters.
     * 
     * @return array The request parameters.
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Put parameters in the request.
     * 
     * @param array $params The parameters to put.
     * 
     * @return void
     */
    public function putParameters($params)
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Get all cookies.
     * 
     * @return array The request cookies.
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Gets the body content.
     * 
     * @return string The request body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sends the communication.
     * 
     * @return Response|false Returns the response or `false` if an error occurred.
     */
    public function send()
    {
        $query = http_build_query($this->params);

        $header = Header::empty();
        
        $header->set('Content-type', 'application/x-www-form-urlencoded');
        $header->set('Content-Length', strlen($query));

        $options = [
            'http' => [
                'header'  => $header->toArray(),
                'method'  => $this->method,
                'content' => $query
            ]
        ];

        $stream = stream_context_create($options);

        $file = fopen($this->uri, 'r', false, $stream);

        if ($file === false) {
            return false;
        }

        $content = stream_get_contents($file);
    
        fclose($file);

        if ($content !== false) {
            return new Response($content);
        }

        return false;
    }
}