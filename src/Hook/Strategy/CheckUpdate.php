<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Hook\Strategy;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckUpdateProviderInterface;
use Psr\Log\LoggerInterface;
use Exception;

class CheckUpdate implements CheckUpdateInterface
{
    private CheckUpdateProviderInterface $provider;
    private LoggerInterface $logger;
    function __construct(CheckUpdateProviderInterface $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function checkUpdate(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response = $this->provider->formatMetaForCheckUpdate($transient->response, $this->provider->getLocalPackageSlug());
            } else {
                $transient->no_update = $this->provider->formatMetaForCheckUpdate($transient->no_update, $this->provider->getLocalPackageSlug());
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
