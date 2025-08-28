<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Hook\Strategy;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoProviderInterface;
use Psr\Log\LoggerInterface;

class CheckInfo implements CheckInfoInterface
{
    private CheckInfoProviderInterface $provider;
    private LoggerInterface $logger;
    public function __construct(CheckInfoProviderInterface $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        // Check if this is for our package
        if (!$arg->slug || $arg->slug !== $this->provider->getLocalPackageSlug()) {
            return $false;
        }
        $this->logger->debug("Providing package info for: " . $arg->slug);

        // Get metadata from remote source
        $meta = $this->provider->formatMetaForCheckInfo();
        if (!$meta) {
            $this->logger->debug("Failed to get metadata for package info");
            return $false;
        }

        $this->logger->debug("Returning package info with properties: " . implode(', ', array_keys((array)$meta)));

        return $meta;
    }
}
