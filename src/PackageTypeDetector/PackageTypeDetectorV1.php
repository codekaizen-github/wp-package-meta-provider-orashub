<?php

namespace CodeKaizen\WPPackageAutoupdater\DetectPackageType;

use CodeKaizen\WPPackageAutoupdater\Contract\DetectPackageType\PackageTypeDetectorInterfaceV1;
use CodeKaizen\WPPackageAutoupdater\Enumeration\PackageType\PackageTypeV1;

class PackageTypeDetectorV1 implements PackageTypeDetectorInterfaceV1
{
    private string $filepath;
    public function __construct(string $filepath)
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw new \InvalidArgumentException("Invalid or inaccessible file path: $filepath");
        }
        $this->filepath = $filepath;
    }
    public function getPackageType(): PackageTypeV1
    {
        // Otherwise auto-detect
        if (strpos($this->filepath, WP_PLUGIN_DIR) === 0) {
            return PackageTypeV1::Plugin;
        }
        if (strpos($this->filepath, get_theme_root()) === 0) {
            return PackageTypeV1::Theme;
        }
        return PackageTypeV1::Unknown;
    }
}
