<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Hook;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\RemoteClientInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\InitializerInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\CheckUpdateProvider\CheckUpdateProviderPlugin;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderLocalPlugin;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderRemote;
use CodeKaizen\WPPackageMetaProviderLocal\Hook\Strategy\CheckUpdate;

class CheckUpdateHookPlugin implements InitializerInterface, CheckUpdateInterface
{
    private string $filePath;
    private RemoteClientInterface $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, RemoteClientInterface $client, LoggerInterface $logger)
    {
        $this->filePath = $filePath;
        $this->client = $client;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'));
    }
    public function checkUpdate(object $transient): object
    {
        $provider = new CheckUpdateProviderPlugin(
            new PackageMetaProviderLocalPlugin($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkUpdate = new CheckUpdate($provider, $this->logger);
        return $checkUpdate->checkUpdate($transient);
    }
}
