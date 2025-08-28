<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Hook;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\InitializerInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\RemoteClientInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\CheckInfoProvider\CheckInfoProviderTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderLocalTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMetaProvider\PackageMetaProviderRemote;
use CodeKaizen\WPPackageMetaProviderLocal\Hook\Strategy\CheckInfo;

use Psr\Log\LoggerInterface;

class CheckInfoHookTheme implements InitializerInterface, CheckInfoInterface
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
        add_filter('plugins_api', array($this, 'checkInfo'), 10, 3);
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        $provider = new CheckInfoProviderTheme(
            new PackageMetaProviderLocalTheme($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkInfo = new CheckInfo($provider, $this->logger);
        return $checkInfo->checkInfo($false, $action, $arg);
    }
}
