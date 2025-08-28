<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface CheckUpdateInterface
{
    public function checkUpdate(object $transient): object;
}
