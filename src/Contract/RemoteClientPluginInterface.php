<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface RemoteClientPluginInterface
{
    public function getPackageMeta(): PackageMetaDetailsPluginInterface;
}
