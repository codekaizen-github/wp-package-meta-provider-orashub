<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Reader;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\Reader\FileContentReaderContract;
use InvalidArgumentException;

class FileContentReader implements FileContentReaderContract
{
    public function read(string $filePath): string
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("Invalid or inaccessible file path: $filePath");
        }
        $kb_in_bytes = 1024;
        $fileData = file_get_contents($filePath, false, null, 0, 8 * $kb_in_bytes);
        if (false === $fileData) {
            $fileData = '';
        }
        return $fileData;
    }
}
