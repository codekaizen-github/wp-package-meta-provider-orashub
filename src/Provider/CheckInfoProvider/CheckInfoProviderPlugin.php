<?php

namespace CodeKaizen\WPPackageAutoupdater\Provider\CheckInfoProviderPlugin;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Formatter\CheckInfoMetaFormatterPlugin;

class CheckInfoProviderPlugin implements CheckInfoProviderInterface
{
    private CheckInfoProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckInfoProviderPackageMetaInterface $localPackageMetaProvider, CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider)
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
        $formatter = new CheckInfoMetaFormatterPlugin($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckInfo();
    }
}
