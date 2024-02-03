<?php

namespace Oraculum\FileSystem\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class MimeType
{
    use NonInstantiable;

    /**
     * Guesses the MIME type from the given file extension.
     * 
     * @param string $extension The file extension.
     * 
     * @return array<int, string> Returns an array of MIME types.
     */
    public static function guessFromFileExtension($extension)
    {
        $mimeTypes = require_once __SOURCE_DIR__ . '/Oraculum/FileSystem/resources/mimes.php';

        return $mimeTypes[$extension] ?? ['application/octet-stream'];
    }
}