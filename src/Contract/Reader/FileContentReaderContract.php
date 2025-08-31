<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract\Reader;

interface FileContentReaderContract
{
    public function read(string $filePath): string;
}
