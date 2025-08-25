<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;


interface CheckUpdateProviderRemotePackageMetaInterface extends CheckUpdateProviderPackageMetaInterface
{
    public function getDownloadURL(): string;
    public function getViewURL(): ?string;
}
