<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\AutoUpdater;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\InitializerInterface;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\ErrorLogHandler; // The StreamHandler sends log messages to a file on your disk
use Monolog\Level;

use CodeKaizen\WPPackageMetaProviderLocal\Client\ORASHub\ORASHubClientTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Hook\CheckUpdateHookTheme;
use CodeKaizen\WPPackageMetaProviderLocal\Hook\CheckInfoHookTheme;

class AutoUpdaterThemeORASHubV1 implements InitializerInterface
{
    private InitializerInterface $checkUpdateHook;
    private InitializerInterface $checkInfoHook;
    public function __construct(string $filePath, string $baseURL, string $metaKey = 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata', string $loggerName = 'WPPackageAutoUpdate', int|string|Level $logLevel = Level::Debug)
    {
        $logger = new Logger($loggerName, [new ErrorLogHandler(3, $logLevel)]);
        $client = new ORASHubClientTheme($baseURL, $metaKey);
        $this->checkUpdateHook = new CheckUpdateHookTheme($filePath, $client, $logger);
        $this->checkInfoHook = new CheckInfoHookTheme($filePath, $client, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
