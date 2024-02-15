<?php

namespace Miscellaneous\Http;

use Miscellaneous\Contracts\FromAny;
use Oraculum\FileSystem\Contracts\FromFile;
use Oraculum\FileSystem\File;
use Oraculum\Http\Header as BaseHeader;
use Oraculum\FileSystem\Support\MimeType as MimeTypeSupport;
use Oraculum\Json\Contracts\FromJson;
use Oraculum\Json\Json;

final class Header extends BaseHeader implements FromFile, FromJson, FromAny
{
    /**
     * Creates a new instance from an file.
     *
     * @param File $file The file to create the instance.
     *
     * @return self The new instance.
     */
    public static function fromFile($file)
    {
        $mimeTypes = MimeTypeSupport::guessFromFileExtension($file->getExtension());

        return self::fromArray([
            'Content-Length' => $file->getSize() ?: 0,
            'Content-Type'   => $mimeTypes[0]
        ]);
    }

    /**
     * Creates a new instance from an JSON.
     *
     * @param Json|array|string $json The JSON to create the instance.
     *
     * @return self The new instance.
     */
    public static function fromJson($json)
    {
        if (is_array($json)) {
            $json = Json::fromArray($json);
        
        } else if (is_string($json)) {
            $json = new Json($json);
        }

        $mimeTypes = MimeTypeSupport::guessFromFileExtension('json');

        return self::fromArray([
            'Content-Length' => $json->getSize(),
            'Content-Type'   => $mimeTypes[0]
        ]);
    }

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
            return self::fromFile($data);
        }

        if ($data instanceof Json) {
            return self::fromJson($data);
        }

        return self::empty();
    }
}