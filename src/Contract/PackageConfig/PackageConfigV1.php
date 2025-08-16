<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract\PackageConfig;

interface PackageConfigV1
{
    public function getVersion(): string;
    public function getPackageSlug(): string;
    public function getSlug(): string;
}
