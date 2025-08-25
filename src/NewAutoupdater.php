<?php

use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\ErrorLogHandler; // The StreamHandler sends log messages to a file on your disk
use Monolog\Level;
use Psr\Log\LoggerInterface;

interface InitializerInterface
{
    public function init(): void;
}
interface CheckUpdateInterface
{
    public function checkUpdate(object $transient): object;
}
interface CheckInfoInterface
{
    /**
     * Add our self-hosted description to the filter
     * This method is called by WordPress when displaying the plugin/theme information
     * in the admin UI (plugin details popup or theme details screen)
     *
     * @param bool $false
     * @param array $action The type of information being requested
     * @param object $arg The arguments passed to the API request
     * @return bool|object
     */
    public function checkInfo(bool $false, array $action, object $arg): bool|object;
}
interface CheckUpdateMetaFormatterInterface
{
    public function formatMetaForCheckUpdate(array $response, string $key): array;
}
interface CheckUpdateProviderInterface extends CheckUpdateMetaFormatterInterface
{
    public function getLocalPackageSlug(): string;
    public function getLocalPackageVersion(): string;
    public function getRemotePackageVersion(): string;
}
interface CheckInfoMetaFormatterInterface
{
    public function formatMetaForCheckInfo(): object;
}
interface CheckInfoProviderInterface extends CheckInfoMetaFormatterInterface
{
    public function getLocalPackageSlug(): string;
}
interface CheckInfoProviderPackageMetaInterface
{
    public function getFullSlug(): string;
}
interface CheckUpdateProviderPackageMetaInterface extends CheckInfoProviderPackageMetaInterface
{
    public function getShortSlug(): string;
    public function getVersion(): string;
}
interface CheckUpdateProviderRemotePackageMetaInterface extends CheckUpdateProviderPackageMetaInterface
{
    public function getDownloadURL(): string;
    public function getViewURL(): ?string;
}
interface PackageMetaDetailsInterface extends CheckUpdateProviderRemotePackageMetaInterface
{
    public function getName(): ?string;
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
interface PackageMetaDetailsThemeInterface extends PackageMetaDetailsInterface {}
interface PackageMetaDetailsPluginInterface extends PackageMetaDetailsInterface
{
    public function getPluginFile(): string;
    public function getSections(): array;
    /** @return array<string,string> */
    public function getNetwork(): bool;
}
interface RemoteClientInterface
{
    public function getPackageMeta(): CheckUpdateProviderRemotePackageMetaInterface;
}
interface RemoteClientPluginInterface
{
    public function getPackageMeta(): PackageMetaDetailsPluginInterface;
}
interface RemoteClientThemeInterface
{
    public function getPackageMeta(): PackageMetaDetailsThemeInterface;
}
class AutoUpdaterPluginORASHubV1 implements InitializerInterface
{
    private InitializerInterface $checkUpdateHook;
    private InitializerInterface $checkInfoHook;
    public function __construct(string $filePath, string $baseURL, $metaKey = 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata', $loggerName = 'WPPackageAutoUpdate')
    {
        $logger = new Logger($loggerName, [new ErrorLogHandler(3, Level::Debug)]);
        $client = new ORASHubClientPlugin($baseURL, $metaKey);
        $this->checkUpdateHook = new CheckUpdateHookPlugin($filePath, $client, $logger);
        $this->checkInfoHook = new CheckInfoHookPlugin($filePath, $client, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
class AutoUpdaterThemeORASHubV1 implements InitializerInterface
{
    private InitializerInterface $checkUpdateHook;
    private InitializerInterface $checkInfoHook;
    public function __construct(string $filePath, string $baseURL, $metaKey = 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata', $loggerName = 'WPPackageAutoUpdate')
    {
        $logger = new Logger($loggerName, [new ErrorLogHandler(3, Level::Debug)]);
        $client = new ORASHubClientTheme($baseURL, $metaKey);
        $this->checkUpdateHook = new CheckUpdateHookTheme($filePath, $client, $logger);
        $this->checkInfoHook = new CheckInfoHookTheme($filePath, $client, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
class CheckUpdateHookPlugin implements InitializerInterface, CheckUpdateInterface
{
    private string $filePath;
    private RemoteClientInterface $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, RemoteClientInterface $client, LoggerInterface $logger)
    {
        $this->filePath = $filePath;
        $this->client = $client;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'));
    }
    public function checkUpdate(object $transient): object
    {
        $provider = new CheckUpdateProviderPlugin(
            new PackageMetaProviderLocalPlugin($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkUpdate = new CheckUpdate($provider, $this->logger);
        return $checkUpdate->checkUpdate($transient);
    }
}
class CheckInfoHookPlugin implements InitializerInterface, CheckInfoInterface
{
    private string $filePath;
    private ORASHubClientPlugin $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, ORASHubClientPlugin $client, LoggerInterface $logger)
    {
        $this->filePath = $filePath;
        $this->client = $client;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('plugins_api', array($this, 'checkInfo'), 10, 3);
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        $provider = new CheckInfoProviderPlugin(
            new PackageMetaProviderLocalPlugin($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkInfo = new CheckInfo($provider, $this->logger);
        return $checkInfo->checkInfo($false, $action, $arg);
    }
}
class CheckUpdateHookTheme implements InitializerInterface, CheckUpdateInterface
{
    private string $filePath;
    private RemoteClientInterface $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, RemoteClientInterface $client, LoggerInterface $logger)
    {
        $this->filePath = $filePath;
        $this->client = $client;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'));
    }
    public function checkUpdate(object $transient): object
    {
        $provider = new CheckUpdateProviderTheme(
            new PackageMetaProviderLocalTheme($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkUpdate = new CheckUpdate($provider, $this->logger);
        return $checkUpdate->checkUpdate($transient);
    }
}
class CheckInfoHookTheme implements InitializerInterface, CheckInfoInterface
{
    private string $filePath;
    private ORASHubClientTheme $client;
    private LoggerInterface $logger;
    public function __construct(string $filePath, ORASHubClientTheme $client, LoggerInterface $logger)
    {
        $this->filePath = $filePath;
        $this->client = $client;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('plugins_api', array($this, 'checkInfo'), 10, 3);
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        $provider = new CheckInfoProviderTheme(
            new PackageMetaProviderLocalTheme($this->filePath),
            new PackageMetaProviderRemote($this->client),
        );
        $checkInfo = new CheckInfo($provider, $this->logger);
        return $checkInfo->checkInfo($false, $action, $arg);
    }
}
class CheckUpdate implements CheckUpdateInterface
{
    private CheckUpdateProviderInterface $provider;
    private LoggerInterface $logger;
    function __construct(CheckUpdateProviderInterface $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function checkUpdate(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response = $this->provider->formatMetaForCheckUpdate($transient->response, $this->provider->getLocalPackageSlug());
            } else {
                $transient->no_update = $this->provider->formatMetaForCheckUpdate($transient->no_update, $this->provider->getLocalPackageSlug());
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
class CheckInfo implements CheckInfoInterface
{
    private CheckInfoProviderInterface $provider;
    private LoggerInterface $logger;
    public function __construct(CheckInfoProviderInterface $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        // Check if this is for our package
        if (!$arg->slug || $arg->slug !== $this->provider->getLocalPackageSlug()) {
            return $false;
        }

        $this->logger->log(Level::Debug, "Providing package info for: " . $arg->slug);

        // Get metadata from remote source
        $meta = $this->provider->formatMetaForCheckInfo();
        if (!$meta) {
            $this->logger->log(Level::Debug, "Failed to get metadata for package info");
            return $false;
        }

        $this->logger->log(Level::Debug, "Returning package info with properties: " . implode(', ', array_keys((array)$meta)));

        return $meta;
    }
}
class CheckInfoMetaFormatterPlugin implements CheckInfoMetaFormatterInterface
{
    private PackageMetaDetailsPluginInterface $provider;
    public function __construct(PackageMetaDetailsPluginInterface $provider)
    {
        $this->provider = $provider;
    }
    public function formatMetaForCheckInfo(): object
    {
        $stdObj = new stdClass();
        $stdObj->name = $this->provider->getName();
        $stdObj->slug = $this->provider->getShortSlug();
        $stdObj->version = $this->provider->getVersion();
        $stdObj->author = $this->provider->getAuthor();
        // $stdObj->author_profile
        $stdObj->requires = $this->provider->getRequiresWordPressVersion();
        $stdObj->tested = $this->provider->getTested();
        $stdObj->requires_php = $this->provider->getRequiresPHPVersion();
        $stdObj->homepage = $this->provider->getViewURL();
        $stdObj->download_link = $this->provider->getDownloadURL();
        $stdObj->update_uri = $this->provider->getDownloadURL();
        // $stdObj->last_updated
        $stdObj->sections = $this->provider->getSections();
        $stdObj->tags = $this->provider->getTags();
        // WordPress expects these properties for plugin information
        $stdObj->external = true; // indicates this is an external package
        return $stdObj;
    }
}
class CheckInfoMetaFormatterTheme implements CheckInfoMetaFormatterInterface
{
    private PackageMetaDetailsThemeInterface $provider;
    public function __construct(PackageMetaDetailsThemeInterface $provider)
    {
        $this->provider = $provider;
    }
    public function formatMetaForCheckInfo(): object
    {
        $stdObj = new stdClass();
        $stdObj->name = $this->provider->getName();
        $stdObj->slug = $this->provider->getShortSlug();
        $stdObj->version = $this->provider->getVersion();
        $stdObj->author = $this->provider->getAuthor();
        // $stdObj->author_profile
        $stdObj->requires = $this->provider->getRequiresWordPressVersion();
        $stdObj->tested = $this->provider->getTested();
        $stdObj->requires_php = $this->provider->getRequiresPHPVersion();
        $stdObj->homepage = $this->provider->getViewURL();
        $stdObj->download_link = $this->provider->getDownloadURL();
        $stdObj->update_uri = $this->provider->getDownloadURL();
        // $stdObj->last_updated
        $stdObj->tags = $this->provider->getTags();
        return $stdObj;
    }
}
class CheckUpdateProviderPlugin implements CheckUpdateProviderInterface
{
    private CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider, CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
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
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $formatter = new CheckUpdateMetaFormatterPlugin($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckUpdate($response, $key);
    }
}
class CheckUpdateProviderTheme implements CheckUpdateProviderInterface
{
    private CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckUpdateProviderPackageMetaInterface $localPackageMetaProvider, CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
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
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $formatter = new CheckUpdateMetaFormatterTheme($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckUpdate($response, $key);
    }
}
class CheckInfoProviderPlugin implements CheckInfoProviderInterface
{
    private CheckInfoProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckInfoProviderPackageMetaInterface $localPackageMetaProvider, CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function formatMetaForCheckInfo(): object
    {
        $formatter = new CheckInfoMetaFormatterPlugin($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckInfo();
    }
}
class CheckInfoProviderTheme implements CheckInfoProviderInterface
{
    private CheckInfoProviderPackageMetaInterface $localPackageMetaProvider;
    private CheckInfoProviderPackageMetaInterface $remotePackageMetaProvider;
    public function __construct(CheckInfoProviderPackageMetaInterface $localPackageMetaProvider, CheckUpdateProviderPackageMetaInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function formatMetaForCheckInfo(): object
    {
        $formatter = new CheckInfoMetaFormatterTheme($this->localPackageMetaProvider, $this->remotePackageMetaProvider);
        return $formatter->formatMetaForCheckInfo();
    }
}
class CheckUpdateMetaFormatterPlugin implements CheckUpdateMetaFormatterInterface
{
    private CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider;
    public function __construct(CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider)
    {
        $this->packageMetaProvider = $packageMetaProvider;
    }
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->packageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->packageMetaProvider->getVersion();
        $metaObject->package = $this->packageMetaProvider->getDownloadURL();
        $metaObject->url = $this->packageMetaProvider->getViewURL();
        $response[$key] = $metaObject;
        return $response;
    }
}
class CheckUpdateMetaFormatterTheme implements CheckUpdateMetaFormatterInterface
{
    private CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider;
    public function __construct(CheckUpdateProviderRemotePackageMetaInterface $packageMetaProvider)
    {
        $this->packageMetaProvider = $packageMetaProvider;
    }
    public function formatMetaForCheckUpdate(array $response, string $key): array
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->packageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->packageMetaProvider->getVersion();
        $metaObject->package = $this->packageMetaProvider->getDownloadURL();
        $metaObject->url = $this->packageMetaProvider->getViewURL();
        $response[$key] = (array) $metaObject;
        return $response;
    }
}
class PackageMetaProviderLocalPlugin implements CheckUpdateProviderPackageMetaInterface, CheckInfoProviderPackageMetaInterface
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
class ORASHubClientPlugin implements RemoteClientPluginInterface, RemoteClientInterface
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
    public function getPackageMeta(): PackageMetaDetailsPluginInterface
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
        return new ORASHubPackageMetaFromObjectPlugin($meta);
    }
}
class ORASHubClientTheme implements RemoteClientThemeInterface, RemoteClientInterface
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
    public function getPackageMeta(): PackageMetaDetailsThemeInterface
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
        return new ORASHubPackageMetaFromObjectTheme($meta);
    }
}
class ORASHubPackageMetaRulePlugin extends Simple
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
class ORASHubPackageMetaRuleTheme extends Simple
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
class ORASHubPackageMetaFromObjectPlugin implements PackageMetaDetailsPluginInterface
{
    private object $stdObj;
    public function __construct(object $stdObj)
    {
        Validator::create(new ORASHubPackageMetaRulePlugin())->check($stdObj);
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
class ORASHubPackageMetaFromObjectTheme implements PackageMetaDetailsThemeInterface
{
    private object $stdObj;
    public function __construct(object $stdObj)
    {
        Validator::create(new ORASHubPackageMetaRuleTheme())->check($stdObj);
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
class PackageMetaProviderRemote implements CheckUpdateProviderRemotePackageMetaInterface, CheckInfoProviderPackageMetaInterface
{
    private RemoteClientInterface $remoteClient;
    public function __construct(RemoteClientInterface $remoteClient)
    {
        $this->remoteClient = $remoteClient;
    }
    public function getVersion(): string
    {
        return $this->remoteClient->getPackageMeta()->getVersion();
    }
    public function getShortSlug(): string
    {
        return $this->remoteClient->getPackageMeta()->getShortSlug();
    }
    public function getFullSlug(): string
    {
        return $this->remoteClient->getPackageMeta()->getFullSlug();
    }
    public function getDownloadURL(): string
    {
        return $this->remoteClient->getPackageMeta()->getDownloadURL();
    }
    public function getViewURL(): ?string
    {
        return $this->remoteClient->getPackageMeta()->getViewURL();
    }
}
