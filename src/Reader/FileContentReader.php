<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Reader;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\Reader\FileContentReaderContract;

class FileContentReader implements FileContentReaderContract
{
    public function read(string $filePath): string
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \InvalidArgumentException("Invalid or inaccessible file path: $filePath");
        }
        $fileData = file_get_contents($filePath, false, null, 0, 8 * KB_IN_BYTES);
        if (false === $fileData) {
            $fileData = '';
        }
        return $fileData;
    }
}
