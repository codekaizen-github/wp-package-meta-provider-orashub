<?php

namespace CodeKaizen\WPPackageAutoupdater\Provider\PackageMetaProvider;

use CodeKaizen\WPPackageAutoupdater\Contract\CheckUpdateProviderPackageMetaInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\CheckInfoProviderPackageMetaInterface;
use Respect\Validation\Validator;
use Respect\Validation\Rules;


class PackageMetaProviderLocalTheme implements CheckUpdateProviderPackageMetaInterface, CheckInfoProviderPackageMetaInterface
{
    protected string $filePath;
    protected string $fullSlug;
    protected string $shortSlug;
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
        $this->fullSlug = basename(dirname($this->filePath));
        $this->shortSlug = $this->fullSlug;
    }
    /**
     * @return string
     * @throws Respect\Validation\Exceptions\ValidationException If the package version number does not exist or is not semver.
     */
    public function getVersion(): string
    {
        $packageData = $this->getPackageMeta();
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
    public function getFullSlug(): string
    {
        return $this->fullSlug;
    }
    /**
     * Slug minus any prefix. Should not contain a "/".
     *
     * @return string
     */
    public function getShortSlug(): string
    {
        return $this->shortSlug;
    }
    protected function getPackageMeta()
    {
        if (! function_exists('wp_get_theme')) {
            require_once ABSPATH . 'wp-includes/theme.php';
        }
        $theme_data = wp_get_theme($this->fullSlug);

        // Define all possible theme headers
        $headers = array(
            'Name' => 'Theme Name',
            'ThemeURI' => 'Theme URI',
            'Author' => 'Author',
            'AuthorURI' => 'Author URI',
            'Description' => 'Description',
            'Version' => 'Version',
            'RequiresWP' => 'Requires at least',
            'TestedWP' => 'Tested up to',
            'RequiresPHP' => 'Requires PHP',
            'License' => 'License',
            'LicenseURI' => 'License URI',
            'TextDomain' => 'Text Domain',
            'Tags' => 'Tags',
            'DomainPath' => 'Domain Path',
            'UpdateURI' => 'Update URI',
            'Template' => 'Template',
            'Status' => 'Status'
        );

        // Convert WP_Theme object to array for consistency
        $result = array();
        foreach ($headers as $key => $header) {
            $value = $theme_data->get($key);
            if ($value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
