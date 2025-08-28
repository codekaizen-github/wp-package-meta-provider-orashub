<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface RemoteClientPluginInterface
{
    public function getPackageMeta(): PackageMetaDetailsPluginInterface;
}
