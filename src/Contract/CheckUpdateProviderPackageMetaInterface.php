<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckUpdateProviderPackageMetaInterface extends CheckInfoProviderPackageMetaInterface
{
    public function getShortSlug(): string;
    public function getVersion(): string;
}
