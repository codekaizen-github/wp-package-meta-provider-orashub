<?php

namespace CodeKaizen\WPPackageAutoupdater\Provider\CheckInfoProvider;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Formatter\CheckInfoMetaFormatterTheme;

class CheckInfoProviderTheme implements CheckInfoProviderInterface
{
    private CheckInfoProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckInfoProviderPackageMetaInterface $localPackageMetaProvider, CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function formatMetaForCheckInfo(): object
    {
        $formatter = new CheckInfoMetaFormatterTheme($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckInfo();
    }
}
