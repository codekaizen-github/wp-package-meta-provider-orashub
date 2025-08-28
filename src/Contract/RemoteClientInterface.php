<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface RemoteClientInterface
{
    public function getPackageMeta(): CheckUpdateProviderRemotePackageMetaInterface;
}
