<?php

namespace CodeKaizen\WPPackageAutoupdater\Hook;

use CodeKaizen\WPPackageAutoupdater\Contract\InitializerInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoInterface;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\ORASHubClientPlugin;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoupdater\Provider\CheckInfoProviderPlugin\CheckInfoProviderPlugin;
use CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider\PackageMetaProviderLocalPlugin;
use CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider\PackageMetaProviderRemote;
use CodeKaizen\WPPackageAutoupdater\Hook\Strategy\CheckInfo;

class CheckInfoHookPlugin implements InitializerInterface, CheckInfoInterface
{
    private string $filePath;
    private ORASHubClientPlugin $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, ORASHubClientPlugin $client, LoggerInterface $logger)
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
        $provider = new CheckInfoProviderPlugin(
            new PackageMetaProviderLocalPlugin($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkInfo = new CheckInfo($provider, $this->logger);
        return $checkInfo->checkInfo($false, $action, $arg);
    }
}
