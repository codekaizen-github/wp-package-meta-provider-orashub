<?php

use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

interface PreSetSiteTransientUpdateHookProviderInterface
{
    public function getLocalPackageSlug(): string;
    public function getLocalPackageVersion(): string;
    public function getRemotePackageVersion(): string;
    public function formatMetaForTransient(): object;
}
interface PreSetSiteTransientUpdateHookInterface
{
    public function handle(object $transient): object;
}
class PreSetSiteTransientPluginUpdateHookV1 implements PreSetSiteTransientUpdateHookInterface
{
    private PreSetSiteTransientUpdateHookProviderInterface $provider;
    private Psr\Log\LoggerInterface $logger;
    function __construct(PreSetSiteTransientUpdateHookProviderInterface $provider, Psr\Log\LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function handle(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response[$this->provider->getLocalPackageSlug()] = $this->provider->formatMetaForTransient();
            } else {
                $transient->no_update[$this->provider->getLocalPackageSlug()] = $this->provider->formatMetaForTransient();
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
class PreSetSiteTransientThemeUpdateHookV1 implements PreSetSiteTransientUpdateHookInterface
{
    private PreSetSiteTransientUpdateHookProviderInterface $provider;
    private Psr\Log\LoggerInterface $logger;
    function __construct(PreSetSiteTransientUpdateHookProviderInterface $provider, Psr\Log\LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function handle(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response[$this->provider->getLocalPackageSlug()] = (array) $this->provider->formatMetaForTransient();
            } else {
                $transient->no_update[$this->provider->getLocalPackageSlug()] = (array) $this->provider->formatMetaForTransient();
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
interface PackageMetaForUpdateCheckProviderInterface
{
    public function getShortSlug(): string;
    public function getFullSlug(): string;
    public function getVersion(): string;
}
interface PackageMetaForDetailsProviderInterface extends PackageMetaForUpdateCheckProviderInterface
{
    public function getDownloadURL(): string;
    public function getName(): ?string;
    public function getViewURL(): ?string;
    public function getTested(): ?string;
    public function getStable(): ?string;
    /** @return string[] */
    public function getTags(): array;
    public function getAuthor(): ?string;
    public function getAuthorURL(): ?string;
    public function getLicense(): ?string;
    public function getLicenseURL(): ?string;
    public function getShortDescription(): ?string;
    public function getDescription(): ?string;
    public function getRequiresWordPressVersion(): ?string;
    public function getRequiresPHPVersion(): ?string;
    public function getTextDomain(): ?string;
    public function getDomainPath(): ?string;
    /** @return string[] */
    public function getRequiresPlugins(): array;
}
interface PackageMetaForDetailsProviderThemeInterface extends PackageMetaForDetailsProviderInterface {}
interface PackageMetaForDetailsProviderPluginInterface extends PackageMetaForDetailsProviderInterface
{
    public function getPluginFile(): string;
    public function getSections(): array;
    /** @return array<string,string> */
    public function getNetwork(): bool;
}
interface PackageMetaProviderInterface extends PackageMetaProviderInterface {}
interface FormatMetaForTransientProviderInterface
{
    public function formatMetaForTransient(): object;
}
class PreSetSiteTransientUpdateHookProviderV1 implements PreSetSiteTransientUpdateHookProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForUpdateCheckProviderInterface $remotePackageMetaProvider;
    private FormatMetaForTransientProviderInterface $formatMetaForTransientProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForUpdateCheckProviderInterface $remotePackageMetaProvider, FormatMetaForTransientProviderInterface $formatMetaForTransientProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
        $this->formatMetaForTransientProvider = $formatMetaForTransientProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function getLocalPackageVersion(): string
    {
        return $this->localPackageMetaProvider->getVersion();
    }
    public function getRemotePackageVersion(): string
    {
        return $this->remotePackageMetaProvider->getVersion();
    }
    public function formatMetaForTransient(): object
    {
        // formatter will also have joint access to localPackageMetaProvider and remotePackageMetaProvider
        return $this->formatMetaForTransientProvider->formatMetaForTransient();
    }
}
class FormatMetaForTransientProviderPluginV1 implements FormatMetaForTransientProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForDetailsProviderPluginInterface $remotePackageMetaProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForDetailsProviderPluginInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function formatMetaForTransient(): object
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->localPackageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->remotePackageMetaProvider->getVersion();
        $metaObject->package = $this->remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $this->remotePackageMetaProvider->getViewURL();
        return $metaObject;
    }
}
class FormatMetaForTransientProviderThemeV1 implements FormatMetaForTransientProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForDetailsProviderThemeInterface $remotePackageMetaProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForDetailsProviderThemeInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function formatMetaForTransient(): object
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->localPackageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->remotePackageMetaProvider->getVersion();
        $metaObject->package = $this->remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $this->remotePackageMetaProvider->getViewURL();
        return $metaObject;
    }
}
class PackageMetaForUpdateCheckProviderPluginLocal implements PackageMetaForUpdateCheckProviderInterface
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
        $this->fullSlug = plugin_basename($this->filePath);
        // Get the last segment after any slash
        $lastSegment = basename($this->fullSlug);
        // Remove extension (if any) to get just the filename
        $this->shortSlug = pathinfo($lastSegment, PATHINFO_FILENAME);
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
        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return get_plugin_data($this->filePath, false, false);
    }
}
class PackageMetaForUpdateCheckProviderThemeLocal implements PackageMetaForUpdateCheckProviderInterface
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

interface RemoteClientPlugin
{
    public function getPackageMeta(): PackageMetaForDetailsProviderPluginInterface;
}
interface RemoteClientTheme
{
    public function getPackageMeta(): PackageMetaForDetailsProviderThemeInterface;
}
class ORASHubClientPlugin implements RemoteClientPlugin
{
    private string $baseURL;
    private string $metaAnnotationKey;
    public function __construct(string $baseURL, string $metaAnnotationKey)
    {
        Validator::create(new Rules\Url())->check($baseURL);
        $this->baseURL = $baseURL;
        $this->metaAnnotationKey = $metaAnnotationKey;
    }
    protected function getMetaURL()
    {
        return $this->baseURL . 'manifest';
    }
    // todo - format the data and return it as an interface which is loose enough to be interpreted by Themes and Plugins alike
    public function getPackageMeta(): PackageMetaForDetailsProviderPluginInterface
    {
        $request = wp_remote_get($this->getMetaURL());

        if (is_wp_error($request)) {
            throw new \Exception('Request to ' . $this->getMetaURL() . ' failed with error ' . $request->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($request);
        if ($status_code !== 200) {
            throw new Exception('Request to ' . $this->getMetaURL() . ' failed with status code ' . $status_code);
        }

        $body = wp_remote_retrieve_body($request);
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new Exception('Invalid JSON returned by request to ' . $this->getMetaURL());
        }
        if (!isset($json['annotations'][$this->metaAnnotationKey])) {
            throw new Exception('Meta annotation key is unset' . $this->metaAnnotationKey);
        }
        $meta_json = $json['annotations'][$this->metaAnnotationKey];
        $meta = json_decode($meta_json, false);
        return new PackageMetaORASHubFromObjectPlugin($meta);
    }
}
class ORASHubClientTheme implements RemoteClientTheme
{
    private string $baseURL;
    private string $metaAnnotationKey;
    public function __construct(string $baseURL, string $metaAnnotationKey)
    {
        Validator::create(new Rules\Url())->check($baseURL);
        $this->baseURL = $baseURL;
        $this->metaAnnotationKey = $metaAnnotationKey;
    }
    protected function getMetaURL()
    {
        return $this->baseURL . 'manifest';
    }
    // todo - format the data and return it as an interface which is loose enough to be interpreted by Themes and Plugins alike
    public function getPackageMeta(): PackageMetaForDetailsProviderThemeInterface
    {
        $request = wp_remote_get($this->getMetaURL());

        if (is_wp_error($request)) {
            throw new \Exception('Request to ' . $this->getMetaURL() . ' failed with error ' . $request->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($request);
        if ($status_code !== 200) {
            throw new Exception('Request to ' . $this->getMetaURL() . ' failed with status code ' . $status_code);
        }

        $body = wp_remote_retrieve_body($request);
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new Exception('Invalid JSON returned by request to ' . $this->getMetaURL());
        }
        if (!isset($json['annotations'][$this->metaAnnotationKey])) {
            throw new Exception('Meta annotation key is unset' . $this->metaAnnotationKey);
        }
        $meta_json = $json['annotations'][$this->metaAnnotationKey];
        $meta = json_decode($meta_json, false);
        return new PackageMetaORASHubFromObjectTheme($meta);
    }
}
class PackageMetaPluginORASHubRule extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_object($input)) {
            return false;
        }

        return Validator::create(new Rules\Attribute('version', new Rules\Version(), true))
            ->create(new Rules\Attribute('shortSlug', new Rules\StringType(), true))
            ->create(new Rules\Attribute('fullSlug', new Rules\StringType(), true))->isValid($input);
    }
}
class PackageMetaThemeORASHubRule extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_object($input)) {
            return false;
        }

        return Validator::create(new Rules\Attribute('version', new Rules\Version(), true))
            ->create(new Rules\Attribute('shortSlug', new Rules\StringType(), true))
            ->create(new Rules\Attribute('fullSlug', new Rules\StringType(), true))->isValid($input);
    }
}
class PackageMetaORASHubFromObjectPlugin implements PackageMetaForDetailsProviderPluginInterface
{
    private object $stdObj;
    public function __construct(object $stdObj)
    {
        Validator::create(new PackageMetaPluginORASHubRule())->check($stdObj);
        $this->stdObj = $stdObj;
    }
    public function getShortSlug(): string
    {
        return $this->stdObj->shortSlug;
    }
    public function getFullSlug(): string
    {
        return $this->stdObj->fullSlug;
    }
    public function getVersion(): string
    {
        return $this->stdObj->version;
    }
    public function getDownloadURL(): string
    {
        return $this->stdObj->downloadURL;
    }
    public function getName(): ?string
    {
        return $this->stdObj->name;
    }
    public function getViewURL(): ?string
    {
        return $this->stdObj->viewURL;
    }
    public function getTested(): ?string
    {
        return $this->stdObj->tested;
    }
    public function getStable(): ?string
    {
        return $this->stdObj->stable;
    }
    public function getTags(): array
    {
        return $this->stdObj->tags;
    }
    public function getAuthor(): ?string
    {
        return $this->stdObj->author;
    }
    public function getAuthorURL(): ?string
    {
        return $this->stdObj->authorURL;
    }
    public function getLicense(): ?string
    {
        return $this->stdObj->license;
    }
    public function getLicenseURL(): ?string
    {
        return $this->stdObj->licenseURL;
    }
    public function getShortDescription(): ?string
    {
        return $this->stdObj->shortDescription;
    }
    public function getDescription(): ?string
    {
        return $this->stdObj->description;
    }
    public function getRequiresWordPressVersion(): ?string
    {
        return $this->stdObj->requiresWordPressVersion;
    }
    public function getRequiresPHPVersion(): ?string
    {
        return $this->stdObj->requiresPHPVersion;
    }
    public function getTextDomain(): ?string
    {
        return $this->stdObj->textDomain;
    }
    public function getDomainPath(): ?string
    {
        return $this->stdObj->domainPath;
    }
    public function getRequiresPlugins(): array
    {
        return $this->stdObj->requiresPlugins;
    }
    public function getPluginFile(): string
    {
        return $this->stdObj->pluginFile;
    }
    public function getSections(): array
    {
        return $this->stdObj->sections;
    }
    public function getNetwork(): bool
    {
        return $this->stdObj->network;
    }
}
class PackageMetaORASHubFromObjectTheme implements PackageMetaForDetailsProviderThemeInterface
{
    private object $stdObj;
    public function __construct(object $stdObj)
    {
        Validator::create(new PackageMetaThemeORASHubRule())->check($stdObj);
        $this->stdObj = $stdObj;
    }
    public function getShortSlug(): string
    {
        return $this->stdObj->shortSlug;
    }
    public function getFullSlug(): string
    {
        return $this->stdObj->fullSlug;
    }
    public function getVersion(): string
    {
        return $this->stdObj->version;
    }
    public function getDownloadURL(): string
    {
        return $this->stdObj->downloadURL;
    }
    public function getName(): ?string
    {
        return $this->stdObj->name;
    }
    public function getViewURL(): ?string
    {
        return $this->stdObj->viewURL;
    }
    public function getTested(): ?string
    {
        return $this->stdObj->tested;
    }
    public function getStable(): ?string
    {
        return $this->stdObj->stable;
    }
    public function getTags(): array
    {
        return $this->stdObj->tags;
    }
    public function getAuthor(): ?string
    {
        return $this->stdObj->author;
    }
    public function getAuthorURL(): ?string
    {
        return $this->stdObj->authorURL;
    }
    public function getLicense(): ?string
    {
        return $this->stdObj->license;
    }
    public function getLicenseURL(): ?string
    {
        return $this->stdObj->licenseURL;
    }
    public function getShortDescription(): ?string
    {
        return $this->stdObj->shortDescription;
    }
    public function getDescription(): ?string
    {
        return $this->stdObj->description;
    }
    public function getRequiresWordPressVersion(): ?string
    {
        return $this->stdObj->requiresWordPressVersion;
    }
    public function getRequiresPHPVersion(): ?string
    {
        return $this->stdObj->requiresPHPVersion;
    }
    public function getTextDomain(): ?string
    {
        return $this->stdObj->textDomain;
    }
    public function getDomainPath(): ?string
    {
        return $this->stdObj->domainPath;
    }
    public function getRequiresPlugins(): array
    {
        return $this->stdObj->requiresPlugins;
    }
}
interface PackageMetaUnwrapperInterface
{
    public function getVersion(PackageMetaForDetailsProviderInterface $packageMeta): string;
    public function getShortSlug(PackageMetaForDetailsProviderInterface $packageMeta): string;
    public function getFullSlug(PackageMetaForDetailsProviderInterface $packageMeta): string;
}
class PackageMetaUnwrapper implements PackageMetaUnwrapperInterface
{
    public function getVersion(PackageMetaForDetailsProviderInterface $packageMeta): string
    {
        return $packageMeta->getVersion();
    }
    public function getShortSlug(PackageMetaForDetailsProviderInterface $packageMeta): string
    {
        return $packageMeta->getShortSlug();
    }
    public function getFullSlug(PackageMetaForDetailsProviderInterface $packageMeta): string
    {
        return $packageMeta->getFullSlug();
    }
}
class PackageMetaForUpdateCheckProviderRemotePlugin implements PackageMetaForUpdateCheckProviderInterface
{
    private RemoteClientPlugin $remoteClient;
    private PackageMetaUnwrapperInterface $packageMetaUnwrapper;
    public function __construct(RemoteClientPlugin $remoteClient, PackageMetaUnwrapperInterface $packageMetaUnwrapper)
    {
        $this->remoteClient = $remoteClient;
        $this->packageMetaUnwrapper = $packageMetaUnwrapper;
    }
    public function getVersion(): string
    {
        return $this->packageMetaUnwrapper->getVersion($this->remoteClient->getPackageMeta());
    }
    public function getShortSlug(): string
    {
        return $this->packageMetaUnwrapper->getShortSlug($this->remoteClient->getPackageMeta());
    }
    public function getFullSlug(): string
    {
        return $this->packageMetaUnwrapper->getFullSlug($this->remoteClient->getPackageMeta());
    }
}
class PackageMetaForUpdateCheckProviderRemoteTheme implements PackageMetaForUpdateCheckProviderInterface
{
    private RemoteClientTheme $remoteClient;
    private PackageMetaUnwrapperInterface $packageMetaUnwrapper;
    public function __construct(RemoteClientTheme $remoteClient, PackageMetaUnwrapperInterface $packageMetaUnwrapper)
    {
        $this->remoteClient = $remoteClient;
        $this->packageMetaUnwrapper = $packageMetaUnwrapper;
    }
    public function getVersion(): string
    {
        return $this->packageMetaUnwrapper->getVersion($this->remoteClient->getPackageMeta());
    }
    public function getShortSlug(): string
    {
        return $this->packageMetaUnwrapper->getShortSlug($this->remoteClient->getPackageMeta());
    }
    public function getFullSlug(): string
    {
        return $this->packageMetaUnwrapper->getFullSlug($this->remoteClient->getPackageMeta());
    }
}
