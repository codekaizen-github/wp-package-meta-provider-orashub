<?php

namespace CodeKaizen\WPPackageAutoupdater\Enumeration\PackageType;

enum PackageTypeV1
{
    case Plugin;
    case Theme;
    case Unknown;
}
