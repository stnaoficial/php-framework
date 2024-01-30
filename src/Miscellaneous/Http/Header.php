<?php

namespace Miscellaneous\Http;

use Oraculum\Contracts\FromMedia;
use Oraculum\Contracts\Media;
use Oraculum\FileSystem\Contracts\FromFile;
use Oraculum\FileSystem\File;
use Oraculum\Http\Header as BaseHeader;
use Oraculum\Http\Support\MimeType as MimeTypeSupport;
use Oraculum\Json\Contracts\FromJson;
use Oraculum\Json\Json;

final class Header extends BaseHeader implements FromFile, FromJson, FromMedia
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
        }

        if (is_string($json)) {
            $json = new Json($json);
        }

        $mimeTypes = MimeTypeSupport::guessFromFileExtension('json');

        return self::fromArray([
            'Content-Length' => $json->getSize(),
            'Content-Type'   => $mimeTypes[0]
        ]);
    }

    /**
     * Creates a new instance from an media.
     *
     * @param Media $media The media to create the instance.
     *
     * @return self The new instance.
     */
    public static function fromMedia($media)
    {
        if ($media instanceof File) {
            return self::fromFile($media);
        }

        if ($media instanceof Json) {
            return self::fromJson($media);
        }

        return self::empty();
    }
}
