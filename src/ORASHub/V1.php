<?php

namespace CodeKaizen\WPPackageAutoupdater\ORASHub;

class V1
{
    public $package_file;
    /**
     * The package current version
     * @var string
     */
    public $current_version;
    /**
     * The package remote update path
     * @var string
     */
    public $update_path;

    /**
     * Override for the UpdateURI from package headers
     * @var string|null
     */
    protected $update_uri;
    /**
     * Whether to delete transients for testing
     * @var bool
     */
    public $delete_transients;
    /**
     * Package Slug (plugin_directory/package_file.php or theme_directory)
     * @var string
     */
    public $package_slug;
    /**
     * Package name (package_file)
     * @var string
     */
    public $slug;
    /**
     * Package type (plugin or theme)
     * @var string
     */
    public $package_type;
    /**
     * Annotation key for package metadata
     * @var string
     */
    protected $meta_annotation_key;

    /**
     * Whether to log debug information
     * @var bool
     */
    protected $enable_logging;

    /**
     * Log prefix for identifying log entries
     * @var string
     */
    protected $log_prefix;

    /**
     * Full URL for manifest endpoint
     * @var string
     */
    protected $manifest_endpoint;

    /**
     * Full URL for download endpoint
     * @var string
     */
    protected $download_endpoint;

    /**
     * Available modes for the updater
     */
    const MODE_PRODUCTION = 'production';
    const MODE_DEBUG = 'debug';

    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $package_file Path to the package file
     * @param array $args Optional arguments
     *        string 'mode' - Either 'production' or 'debug' (default: 'production')
     *        string 'meta_annotation_key' - Key for package metadata in ORAS manifest
     *                                     (default: 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata')
     *        string 'log_prefix' - Prefix for log messages (default: 'WPPackageAutoupdater')
     *        string 'manifest_endpoint' - Full URL for manifest endpoint (default: null, uses UpdateURI + 'manifest')
     *        string 'download_endpoint' - Full URL for download endpoint (default: null, uses UpdateURI + 'download')
     *        string 'update_uri' - Override the UpdateURI from package headers (default: null, uses package header)
     *        string 'type' - Force the package type, either 'plugin' or 'theme' (default: null, auto-detected)
     */
    function __construct($package_file, array $args = [])
    {
        // Process arguments with defaults
        $defaults = [
            'mode' => self::MODE_PRODUCTION,
            'meta_annotation_key' => 'org.codekaizen-github.wp-package-deploy-oras.wp-package-metadata',
            'log_prefix' => 'WPPackageAutoupdater',
            'manifest_endpoint' => null,
            'download_endpoint' => null,
            'update_uri' => null,
            'type' => null
        ];

        $args = array_merge($defaults, $args);

        // Set options based on mode
        $is_debug_mode = ($args['mode'] === self::MODE_DEBUG);
        $this->delete_transients = $is_debug_mode;
        $this->enable_logging = $is_debug_mode;

        // Store configuration values
        $this->meta_annotation_key = $args['meta_annotation_key'];
        $this->log_prefix = $args['log_prefix'];
        $this->manifest_endpoint = $args['manifest_endpoint'];
        $this->download_endpoint = $args['download_endpoint'];
        $this->update_uri = $args['update_uri'];

        // Initialize package
        $this->package_file = $package_file;

        if ($this->delete_transients) {
            $this->delete_update_transients();
        }

        // Determine package type - use provided type or auto-detect
        $this->package_type = $this->get_package_type($args['type']);
        // Set the class public variables based on package type
        if (!in_array($this->package_type, ['plugin', 'theme'])) {
            $this->log("Error: Unable to determine package type. The package must be a valid WordPress plugin or theme.");
            return; // Don't proceed with setup if we can't determine the type
        }

        $this->log("Setting up " . $this->package_type . " updater");

        if ($this->package_type === 'plugin') {
            $this->package_slug = plugin_basename($package_file);
            list($t1, $t2) = explode('/', $this->package_slug);
            $this->slug = str_replace('.php', '', $t2);
            // define the alternative API for plugin updating checking
            add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));
            // Define the alternative response for plugin information checking
            add_filter('plugins_api', array(&$this, 'check_info'), 10, 3);
        } elseif ($this->package_type === 'theme') {
            $theme_dir = basename(dirname($package_file));
            $this->package_slug = $theme_dir;
            $this->slug = $theme_dir;
            // define the alternative API for theme updating checking
            add_filter('pre_set_site_transient_update_themes', array(&$this, 'check_update'));
            // Define the alternative response for theme information checking
            add_filter('themes_api', array(&$this, 'check_info'), 10, 3);
        }
    }

    /**
     * Force package type or detect if the package_file is a plugin or a theme.
     * @param string|null $force_type Optional type to force ('plugin' or 'theme')
     * @return string|null 'plugin', 'theme', or null if not recognized.
     */
    public function get_package_type($force_type = null)
    {
        // If a valid type is provided, use it
        if ($force_type !== null && in_array($force_type, ['plugin', 'theme'])) {
            $this->log("Using forced package type: " . $force_type);
            return $force_type;
        }

        // Otherwise auto-detect
        if (strpos($this->package_file, WP_PLUGIN_DIR) === 0) {
            return 'plugin';
        }
        if (strpos($this->package_file, get_theme_root()) === 0) {
            return 'theme';
        }
        return null;
    }

    /**
     * Get plugin data from file headers
     * @return array Plugin data
     */
    function get_plugin_data()
    {
        if (! function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_data = get_plugin_data($this->package_file);
        return $plugin_data;
    }

    /**
     * Get theme data from file headers
     * @return array Theme data
     */
    function get_theme_data()
    {
        if (! function_exists('wp_get_theme')) {
            require_once ABSPATH . 'wp-includes/theme.php';
        }
        $theme_data = wp_get_theme($this->slug);

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

    /**
     * Get package data based on whether it's a plugin or theme
     * @return array Package data
     */
    function get_package_data()
    {
        if ($this->package_type === 'plugin') {
            return $this->get_plugin_data();
        } elseif ($this->package_type === 'theme') {
            return $this->get_theme_data();
        }
        return array();
    }

    /**
     * Delete update transients for testing purposes
     */
    public function delete_update_transients()
    {
        // Delete plugin-related transients
        delete_site_transient('update_plugins');
        delete_site_transient('update_themes');
        delete_site_transient('update_core');
    }

    /**
     * Add our self-hosted autoupdate package to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update($transient)
    {
        $this->log("Checking for updates", $this->package_slug);

        if (empty($transient->checked)) {
            $this->log("No checked packages in transient, skipping");
            return $transient;
        }
        $meta = $this->get_remote_metadata();
        if (!is_array($meta)) {
            $this->log("Failed to get remote metadata or invalid format");
            return $transient;
        }
        $meta_object = (object) $meta;
        if (!isset($meta_object->version)) {
            return $transient;
        }
        $package_data = $this->get_package_data();
        if (!is_array($package_data) || empty($package_data)) {
            return $transient;
        }
        $current_version = isset($package_data['Version']) ? $package_data['Version'] : '1.0';
        $meta_object->slug = $this->slug;
        $meta_object->new_version = $meta_object->version;

        // Use the override if provided, otherwise use the URI from metadata
        $update_uri = $this->update_uri !== null ? $this->update_uri : $meta_object->update_uri;
        $meta_object->url = $update_uri;

        // Set package URL - use full URL if provided, otherwise append 'download' to UpdateURI
        if ($this->download_endpoint !== null) {
            // Use the provided full URL
            $meta_object->package = $this->download_endpoint;
        } else {
            // Default behavior: append 'download' to the UpdateURI
            $meta_object->package = trailingslashit($update_uri) . 'download';
        }
        // Format response appropriately for plugins vs themes
        if ($this->package_type === 'theme') {
            // Themes require a specific format
            $theme_response = array(
                'theme' => $this->slug,
                'new_version' => $meta_object->version,
                'url' => $meta_object->url,
                'package' => $meta_object->package,
            );
            if (version_compare($current_version, $meta_object->version, '<')) {
                $transient->response[$this->package_slug] = $theme_response;
            } else {
                $transient->no_update[$this->package_slug] = $theme_response;
            }
        } else {
            // Plugins can use the object as is
            if (version_compare($current_version, $meta_object->version, '<')) {
                $transient->response[$this->package_slug] = $meta_object;
            } else {
                $transient->no_update[$this->package_slug] = $meta_object;
            }
        }
        // var_dump(json_encode($transient));
        return $transient;
    }
    /**
     * Add our self-hosted description to the filter
     * This method is called by WordPress when displaying the plugin/theme information
     * in the admin UI (plugin details popup or theme details screen)
     *
     * @param boolean $false
     * @param array $action The type of information being requested
     * @param object $arg The arguments passed to the API request
     * @return bool|object
     */
    public function check_info($false, $action, $arg)
    {
        // Check if this is for our package
        if (!$arg->slug || $arg->slug !== $this->slug) {
            return $false;
        }

        $this->log("Providing package info for: " . $arg->slug);

        // Get metadata from remote source
        $meta = $this->get_remote_metadata();
        if (!$meta) {
            $this->log("Failed to get metadata for package info");
            return $false;
        }

        // Convert to object
        $info_object = (object) $meta;

        // Add additional fields that might be needed based on the action
        if ($this->package_type === 'plugin') {
            // WordPress expects these properties for plugin information
            $info_object->external = true; // Indicates this is an external package

            // Additional plugin-specific enhancements could be added here
        } else if ($this->package_type === 'theme') {
            // Additional theme-specific enhancements could be added here
        }

        $this->log("Returning package info with properties: " . implode(', ', array_keys((array)$info_object)));

        return $info_object;
    }

    /**
     * Fetch and cache remote package metadata from the update_path endpoint.
     * @return array|false
     */
    protected function get_remote_metadata()
    {
        static $cached = null;
        if ($cached !== null) {
            $this->log("Returning cached metadata");
            return $cached;
        }
        // Get the base UpdateURI - either from the override or from package headers
        $update_uri = $this->update_uri;

        if ($update_uri === null) {
            $package_data = $this->get_package_data();
            if (!is_array($package_data) || empty($package_data) || empty($package_data['UpdateURI'])) {
                $this->log("Invalid package data or missing UpdateURI", $package_data);
                return false;
            }
            $update_uri = $package_data['UpdateURI'];
        }

        $this->log("Using update URI: " . $update_uri);

        // Build manifest URL - use full URL if provided, otherwise append 'manifest' to UpdateURI
        if ($this->manifest_endpoint !== null) {
            // Use the provided full URL
            $manifest_url = $this->manifest_endpoint;
        } else {
            // Default behavior: append 'manifest' to the UpdateURI
            $manifest_url = trailingslashit($update_uri) . 'manifest';
        }

        $this->log("Fetching remote metadata from: " . $manifest_url);
        $request = wp_remote_get($manifest_url);

        if (is_wp_error($request)) {
            $this->log("Error fetching manifest", $request->get_error_message());
            return false;
        }

        $status_code = wp_remote_retrieve_response_code($request);
        if ($status_code !== 200) {
            $this->log("Unexpected HTTP status code: " . $status_code);
            return false;
        }

        $body = wp_remote_retrieve_body($request);
        $json = json_decode($body, true);
        if (!is_array($json)) {
            $this->log("Invalid JSON response");
            return false;
        }

        if (!isset($json['annotations'][$this->meta_annotation_key])) {
            $this->log("Missing annotation key in response", $this->meta_annotation_key);
            return false;
        }
        $meta_json = $json['annotations'][$this->meta_annotation_key];
        $meta = json_decode($meta_json, true);
        if (!is_array($meta)) {
            $this->log("Failed to decode metadata JSON");
            return false;
        }

        // Ensure required and recommended fields exist
        $meta = $this->normalize_metadata($meta);

        $this->log("Successfully retrieved metadata", $meta);

        $cached = $meta;
        return $meta;
    }

    /**
     * Normalize and enhance metadata to ensure all expected fields are available
     * @param array $meta The raw metadata from the manifest
     * @return array Enhanced metadata with all expected fields
     */
    protected function normalize_metadata($meta)
    {
        // Core fields that should always be present
        $defaults = [
            'name' => '',
            'slug' => '',
            'version' => '',
            'author' => '',
            'author_profile' => '',
            'requires' => '',        // Minimum WP version
            'tested' => '',          // Tested WP version
            'requires_php' => '',    // Minimum PHP version
            'homepage' => '',
            'download_link' => '',
            'update_uri' => '',
            'last_updated' => '',
            'sections' => [
                'description' => '',
                'installation' => '',
                'changelog' => '',
                'faq' => ''
            ]
        ];

        // Add package type specific defaults
        if ($this->package_type === 'plugin') {
            $defaults['contributors'] = [];
            $defaults['donate_link'] = '';
            $defaults['tags'] = [];
            $defaults['banners'] = [
                'low' => '',
                'high' => ''
            ];
            $defaults['screenshots'] = [];
        } elseif ($this->package_type === 'theme') {
            $defaults['sections']['reviews'] = '';
            $defaults['rating'] = 100;
            $defaults['num_ratings'] = 0;
            $defaults['downloaded'] = 0;
            $defaults['active_installs'] = 0;
            $defaults['theme_url'] = '';
            $defaults['preview_url'] = '';
        }

        // Merge existing metadata with defaults
        $normalized = array_merge($defaults, $meta);

        // Handle special conversions for WordPress UI

        // Convert sections from string to array if needed
        if (isset($meta['sections']) && is_string($meta['sections'])) {
            try {
                $sections = json_decode($meta['sections'], true);
                if (is_array($sections)) {
                    $normalized['sections'] = array_merge($defaults['sections'], $sections);
                }
            } catch (\Exception $e) {
                // Keep original sections
            }
        }

        // Format author with link if profile URL is available
        if (!empty($normalized['author']) && !empty($normalized['author_profile'])) {
            $normalized['author'] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url($normalized['author_profile']),
                esc_html($normalized['author'])
            );
        }

        // Format contributors (if present)
        if ($this->package_type === 'plugin' && isset($meta['contributors']) && is_array($meta['contributors'])) {
            $formatted_contributors = [];
            foreach ($meta['contributors'] as $username => $profile_url) {
                $formatted_contributors[$username] = $profile_url;
            }
            $normalized['contributors'] = $formatted_contributors;
        }

        $this->log("Normalized metadata", array_keys($normalized));

        return $normalized;
    }

    /**
     * Log a debug message if logging is enabled
     * @param string $message The message to log
     * @param mixed $data Optional data to include in the log
     * @return void
     */
    protected function log($message, $data = null)
    {
        if (!$this->enable_logging) {
            return;
        }

        $log_message = "[{$this->log_prefix}] {$message}";

        if ($data !== null) {
            if (is_array($data) || is_object($data)) {
                $log_message .= " - " . print_r($data, true);
            } else {
                $log_message .= " - {$data}";
            }
        }

        // Use WordPress logging if available, otherwise use error_log
        if (function_exists('error_log')) {
            error_log($log_message);
        }
    }
}
