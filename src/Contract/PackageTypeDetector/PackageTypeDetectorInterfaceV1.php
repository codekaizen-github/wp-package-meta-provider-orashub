<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract\DetectPackageType;

use CodeKaizen\WPPackageAutoupdater\Enumeration\PackageType\PackageTypeV1;

interface PackageTypeDetectorInterfaceV1
{
    public function getPackageType(): PackageTypeV1;
}
