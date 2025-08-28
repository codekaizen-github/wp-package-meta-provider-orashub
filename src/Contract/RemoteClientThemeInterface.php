<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface RemoteClientThemeInterface
{
    public function getPackageMeta(): PackageMetaDetailsThemeInterface;
}
