<?php

namespace CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderRemotePackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\RemoteClientInterface;

class PackageMetaProviderRemote implements CheckUpdateProviderRemotePackageMetaInterface, CheckInfoProviderPackageMetaInterface
{
    private RemoteClientInterface $remoteClient;
    public function __construct(RemoteClientInterface $remoteClient)
    {
        $this->remoteClient = $remoteClient;
    }
    public function getVersion(): string
    {
        return $this->remoteClient->getPackageMeta()->getVersion();
    }
    public function getShortSlug(): string
    {
        return $this->remoteClient->getPackageMeta()->getShortSlug();
    }
    public function getFullSlug(): string
    {
        return $this->remoteClient->getPackageMeta()->getFullSlug();
    }
    public function getDownloadURL(): string
    {
        return $this->remoteClient->getPackageMeta()->getDownloadURL();
    }
    public function getViewURL(): ?string
    {
        return $this->remoteClient->getPackageMeta()->getViewURL();
    }
}
