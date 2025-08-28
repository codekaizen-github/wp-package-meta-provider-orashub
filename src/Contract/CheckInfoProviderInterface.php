<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface CheckInfoProviderInterface extends CheckInfoMetaFormatterInterface
{
    public function getLocalPackageSlug(): string;
}
