<?php

namespace CodeKaizen\WPPackageAutoupdater\AutoUpdater;

use CodeKaizen\WPPackageAutoupdater\Contract\InitializerInterface;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\ErrorLogHandler; // The StreamHandler sends log messages to a file on your disk
use Monolog\Level;

use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\ORASHubClientTheme;
use CodeKaizen\WPPackageAutoupdater\Hook\CheckUpdateHookTheme;
use CodeKaizen\WPPackageAutoupdater\Hook\CheckInfoHookTheme;

class AutoUpdaterThemeORASHubV1 implements InitializerInterface
{
    private InitializerInterface $checkUpdateHook;
    private InitializerInterface $checkInfoHook;
    public function __construct(string $filePath, string $baseURL, $metaKey = 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata', $loggerName = 'WPPackageAutoUpdate')
    {
        $logger = new Logger($loggerName, [new ErrorLogHandler(3, Level::Debug)]);
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
