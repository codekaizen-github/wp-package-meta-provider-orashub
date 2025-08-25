<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckUpdateInterface
{
    public function checkUpdate(object $transient): object;
}
