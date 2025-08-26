<?php

namespace CodeKaizen\WPPackageAutoupdater\Hook;

use CodeKaizen\WPPackageAutoupdater\Contract\InitializerInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\RemoteClientInterface;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoupdater\Provider\CheckUpdateProvider\CheckUpdateProviderTheme;
use CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider\PackageMetaProviderLocalTheme;
use CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider\PackageMetaProviderRemote;
use CodeKaizen\WPPackageAutoupdater\Hook\Strategy\CheckUpdate;

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
