<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Provider\CheckUpdateProvider;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateProviderPackageMetaInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateProviderInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Formatter\CheckUpdateMetaFormatterTheme;

class CheckUpdateProviderTheme implements CheckUpdateProviderInterface
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
        $formatter = new CheckUpdateMetaFormatterTheme($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckUpdate($response, $key);
    }
}
