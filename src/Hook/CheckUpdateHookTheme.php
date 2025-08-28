<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Hook;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\InitializerInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\RemoteClientInterface;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\CheckUpdateProvider\CheckUpdateProviderTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderLocalTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderRemote;
use CodeKaizen\WPPackageMetaProviderLocal\Hook\Strategy\CheckUpdate;

class CheckUpdateHookTheme implements InitializerInterface, CheckUpdateInterface
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
        $provider = new CheckUpdateProviderTheme(
            new PackageMetaProviderLocalTheme($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkUpdate = new CheckUpdate($provider, $this->logger);
        return $checkUpdate->checkUpdate($transient);
    }
}
