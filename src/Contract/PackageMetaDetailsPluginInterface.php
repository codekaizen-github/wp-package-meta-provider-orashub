<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface PackageMetaDetailsPluginInterface extends PackageMetaDetailsInterface
{
    public function getPluginFile(): string;
    public function getSections(): array;
    /** @return array<string,string> */
    public function getNetwork(): bool;
}
