<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta;

use Respect\Validation\Validator;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaDetailsPluginContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Reader\FileContentReaderContract;
use CodeKaizen\WPPackageMetaProviderLocal\Parser\PackageMeta\SelectHeadersPackageMetaParser;
use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta\PluginHeadersArrayRule;

class LocalPluginPackageMetaProvider implements PackageMetaDetailsPluginContract
{
    protected string $filePath;
    protected FileContentReaderContract $reader;
    protected string $fullSlug;
    protected string $shortSlug;
    /**
     * @param string $filePath Path to the plugin file
     * @throws \InvalidArgumentException If the file path is invalid
     */
    public function __construct(string $filePath, FileContentReaderContract $reader)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Invalid file path: $filePath");
        }
        $this->filePath = $filePath;
        $this->reader = $reader;
        $basename = basename($filePath);
        $directory = dirname($filePath);
        $directoryBasename = pathinfo($directory, PATHINFO_BASENAME);
        // Includes any .php extension
        $this->fullSlug = $directoryBasename . '/' . $basename;
        // Remove extension (if any) to get just the filename
        $this->shortSlug = pathinfo($basename, PATHINFO_FILENAME);
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getPackageMeta()['Name'];
    }
    /**
     * Full slug, including any directory prefix and any file extension like .php - may contain a "/".
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
    /**
     * @return ?string
     */
    public function getVersion(): ?string
    {
        return $this->getPackageMeta()['Version'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getViewURL(): ?string
    {
        return $this->getPackageMeta()['PluginURI'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getDownloadURL(): ?string
    {
        return $this->getPackageMeta()['UpdateURI'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getTested(): ?string
    {
        return null;
    }
    /**
     * @return ?string
     */
    public function getStable(): ?string
    {
        return null;
    }
    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return [];
    }
    /**
     * @return ?string
     */
    public function getAuthor(): ?string
    {
        return $this->getPackageMeta()['Author'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getAuthorURL(): ?string
    {
        return $this->getPackageMeta()['AuthorURI'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getLicense(): ?string
    {
        return null;
    }
    /**
     * @return ?string
     */
    public function getLicenseURL(): ?string
    {
        return null;
    }
    /**
     * @return ?string
     */
    public function getShortDescription(): ?string
    {
        return $this->getPackageMeta()['Description'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return null;
    }
    /**
     * @return ?string
     */
    public function getRequiresWordPressVersion(): ?string
    {
        return $this->getPackageMeta()['RequiresWP'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getRequiresPHPVersion(): ?string
    {
        return $this->getPackageMeta()['RequiresPHP'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getTextDomain(): ?string
    {
        return $this->getPackageMeta()['TextDomain'] ?? null;
    }
    /**
     * @return ?string
     */
    public function getDomainPath(): ?string
    {
        return $this->getPackageMeta()['DomainPath'] ?? null;
    }
    /**
     * @return string[]
     */
    public function getRequiresPlugins(): array
    {
        return isset($this->getPackageMeta()['RequiresPlugins']) ? array_map('trim', explode(',', $this->getPackageMeta()['RequiresPlugins'])) : [];
    }
    /**
     *
     * @return array<string,string>
     */
    public function getSections(): array
    {
        return [];
    }
    /**
     *
     * @return boolean
     */
    public function getNetwork(): bool
    {
        return (bool) ($this->getPackageMeta()['Network'] ?? false);
    }

    /**
     *
     * @return array<string,string> $metaArray
     */
    protected function getPackageMeta(): array
    {
        $parser = new SelectHeadersPackageMetaParser(
            array(
                'Name'            => 'Plugin Name',
                'PluginURI'       => 'Plugin URI',
                'Version'         => 'Version',
                'Description'     => 'Description',
                'Author'          => 'Author',
                'AuthorURI'       => 'Author URI',
                'TextDomain'      => 'Text Domain',
                'DomainPath'      => 'Domain Path',
                'Network'         => 'Network',
                'RequiresWP'      => 'Requires at least',
                'RequiresPHP'     => 'Requires PHP',
                'UpdateURI'       => 'Update URI',
                'RequiresPlugins' => 'Requires Plugins',
                // Site Wide Only is deprecated in favor of Network.
                // '_sitewide'       => 'Site Wide Only',
            )
        );
        $metaArray = $parser->parse($this->reader->read($this->filePath));
        Validator::create(new PluginHeadersArrayRule())->check($metaArray);
        return $metaArray;
    }
}
