<?php

namespace CodeKaizen\WPPackageAutoupdater\Autoupdater\Plugin;

class PluginV1
{
    protected string $filepath;
    /**
     * @param string $filepath Path to the plugin file
     * @throws \InvalidArgumentException If the file path is invalid
     */
    public function __construct(string $filepath)
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw new \InvalidArgumentException("Invalid or inaccessible file path: $filepath");
        }

        $this->filepath = $filepath;
    }
}
