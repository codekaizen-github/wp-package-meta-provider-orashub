<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Provider\CheckInfoProviderPlugin;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoProviderInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoProviderPackageMetaInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateProviderPackageMetaInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Formatter\CheckInfoMetaFormatterPlugin;

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
