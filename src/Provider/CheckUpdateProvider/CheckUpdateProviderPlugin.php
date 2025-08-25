<?php

namespace CodeKaizen\WPPackageAutoupdater\Provider\CheckUpdateProvider;

use CodeKaizen\WPPackageAutoupdater\Formatter\CheckUpdateMetaFormatterPlugin;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderInterface;

class CheckUpdateProviderPlugin implements CheckUpdateProviderInterface
{
    private CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider, CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function getLocalPackageVersion(): string
    {
        return $this->localPackageMetaProvider->getVersion();
    }
    public function getRemotePackageVersion(): string
    {
        return $this->remotePackageMetaProvider->getVersion();
    }
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $formatter = new CheckUpdateMetaFormatterPlugin($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckUpdate($response, $key);
    }
}
