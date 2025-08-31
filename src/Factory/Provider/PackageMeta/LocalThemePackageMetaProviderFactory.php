<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaDetailsThemeContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

class LocalThemePackageMetaProviderFactory
{
    public string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function create(): PackageMetaDetailsThemeContract
    {
        return new LocalThemePackageMetaProvider(
            $this->filePath,
            new FileContentReader()
        );
    }
}
