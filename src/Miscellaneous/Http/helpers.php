<?php declare(strict_types=1);

if (!function_exists('response')) {
    /**
     * Creates an HTTP response.
     * 
     * @param mixed $media The media of the response.
     * 
     * @return \Miscellaneous\Http\Response Returns the HTTP response.
     */
    function response($media = null)
    {
        return \Miscellaneous\Http\Response::fromMedia($media);
    }
}