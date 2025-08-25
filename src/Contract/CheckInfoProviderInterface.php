<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckInfoProviderInterface extends CheckInfoMetaFormatterInterface
{
    public function getLocalPackageSlug(): string;
}
