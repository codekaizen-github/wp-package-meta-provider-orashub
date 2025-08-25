<?php

namespace CodeKaizen\WPPackageAutoupdater\AutoUpdater;

use CodeKaizen\WPPackageAutoupdater\Contract\InitializerInterface;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\ErrorLogHandler; // The StreamHandler sends log messages to a file on your disk
use Monolog\Level;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\ORASHubClientPlugin;
use CodeKaizen\WPPackageAutoupdater\Hook\CheckUpdateHookPlugin;
use CodeKaizen\WPPackageAutoupdater\Hook\CheckInfoHookPlugin;

class AutoUpdaterPluginORASHubV1 implements InitializerInterface
{
    private InitializerInterface $checkUpdateHook;
    private InitializerInterface $checkInfoHook;
    public function __construct(string $filePath, string $baseURL, $metaKey = 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata', $loggerName = 'WPPackageAutoUpdate')
    {
        $logger = new Logger($loggerName, [new ErrorLogHandler(3, Level::Debug)]);
        $client = new ORASHubClientPlugin($baseURL, $metaKey);
        $this->checkUpdateHook = new CheckUpdateHookPlugin($filePath, $client, $logger);
        $this->checkInfoHook = new CheckInfoHookPlugin($filePath, $client, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
