<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaDetailsPluginContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalPluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

class LocalPluginPackageMetaProviderFactory
{
    public string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function create(): PackageMetaDetailsPluginContract
    {
        return new LocalPluginPackageMetaProvider(
            $this->filePath,
            new FileContentReader()
        );
    }
}
