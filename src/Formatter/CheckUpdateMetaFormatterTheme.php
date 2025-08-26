<?php

namespace CodeKaizen\WPPackageAutoupdater\Formatter;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderRemotePackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateMetaFormatterInterface;
use stdClass;

class CheckUpdateMetaFormatterTheme implements CheckUpdateMetaFormatterInterface
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
        $response[$key] = (array) $metaObject;
        return $response;
    }
}
