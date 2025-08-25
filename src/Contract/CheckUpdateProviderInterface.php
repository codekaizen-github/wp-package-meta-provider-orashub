<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckUpdateProviderInterface extends CheckUpdateMetaFormatterInterface
{
    public function getLocalPackageSlug(): string;
    public function getLocalPackageVersion(): string;
    public function getRemotePackageVersion(): string;
}
