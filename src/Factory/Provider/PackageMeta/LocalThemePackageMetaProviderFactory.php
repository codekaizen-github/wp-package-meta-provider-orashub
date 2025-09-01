<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

class LocalThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract
{
    public string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function create(): ThemePackageMetaContract
    {
        return new LocalThemePackageMetaProvider(
            $this->filePath,
            new FileContentReader()
        );
    }
}
