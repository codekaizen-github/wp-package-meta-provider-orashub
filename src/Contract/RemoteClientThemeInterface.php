<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface RemoteClientThemeInterface
{
    public function getPackageMeta(): PackageMetaDetailsThemeInterface;
}
