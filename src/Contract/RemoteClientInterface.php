<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface RemoteClientInterface
{
    public function getPackageMeta(): CheckUpdateProviderRemotePackageMetaInterface;
}
