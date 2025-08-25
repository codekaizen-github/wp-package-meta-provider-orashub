<?php

namespace CodeKaizen\WPPackageAutoupdater\Formatter;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateMetaFormatterInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderRemotePackageMetaInterface;
use stdClass;

class CheckUpdateMetaFormatterPlugin implements CheckUpdateMetaFormatterInterface
{
    private CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider;
    public function __construct(CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider)
    {
        $this->packageMetaProvider = $packageMetaProvider;
    }
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->packageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->packageMetaProvider->getVersion();
        $metaObject->package = $this->packageMetaProvider->getDownloadURL();
        $metaObject->url = $this->packageMetaProvider->getViewURL();
        $response[$key] = $metaObject;
        return $response;
    }
}
