<?php

namespace CodeKaizen\WPPackageAutoupdater\PackageConfig\Plugin;

use CodeKaizen\WPPackageAutoupdater\Contract\PackageConfig\PackageConfigV1;
use Respect\Validation\Validator;
use Respect\Validation\Rules;

class PluginV1 implements PackageConfigV1
{
    protected string $filePath;
    protected string $packageSlug;
    protected string $slug;
    /**
     * @param string $filePath Path to the plugin file
     * @throws \InvalidArgumentException If the file path is invalid
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \InvalidArgumentException("Invalid or inaccessible file path: $filePath");
        }
        $this->filePath = $filePath;
        $this->packageSlug = plugin_basename($this->filePath);
        // Get the last segment after any slash
        $lastSegment = basename($this->packageSlug);
        // Remove extension (if any) to get just the filename
        $this->slug = pathinfo($lastSegment, PATHINFO_FILENAME);
    }
    public function getVersion(): string
    {
        $packageData = $this->getPackageData();
        $value = $packageData['Version'] ?? null;
        Validator::create(new Rules\StringType(), new Rules\Version())->check($packageData['Version'] ?? null);
        // After validation, we can assert the type
        /** @var string $value Type is guaranteed to be string after validation */
        return $value;
    }
    /**
     * Slug including any prefix - may contain a "/".
     *
     * @return string
     */
    public function getPackageSlug(): string
    {
        return $this->packageSlug;
    }
    /**
     * Slug minus any prefix. Should not contain a "/".
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
    protected function getPackageData()
    {
        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return get_plugin_data($this->filePath, false, false);
    }
}
