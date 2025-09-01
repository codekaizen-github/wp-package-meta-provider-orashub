<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalPluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

class LocalPluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract
{
    public string $filePath;
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    public function create(): PluginPackageMetaContract
    {
        return new LocalPluginPackageMetaProvider(
            $this->filePath,
            new FileContentReader()
        );
    }
}
