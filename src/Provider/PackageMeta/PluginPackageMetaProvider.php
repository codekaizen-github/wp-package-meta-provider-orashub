<?php
/**
 * Local Plugin Package Meta Provider
 *
 * Provides metadata for WordPress plugins installed locally.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta;

use Respect\Validation\Validator;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Reader\FileContentReaderContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Parser\PackageMeta\SelectHeadersPackageMetaParser;
use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta\PluginHeadersArrayRule;
use InvalidArgumentException;

/**
 * Provider for local WordPress plugin package metadata.
 *
 * Reads and parses metadata from plugin files in the local filesystem.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProvider implements PluginPackageMetaContract {

	/**
	 * Path to the plugin file.
	 *
	 * @var string
	 */
	protected string $filePath;

	/**
	 * File content reader instance.
	 *
	 * @var FileContentReaderContract
	 */
	protected FileContentReaderContract $reader;

	/**
	 * Full plugin slug including directory prefix and file extension.
	 *
	 * @var string
	 */
	protected string $fullSlug;

	/**
	 * Short plugin slug without directory prefix or file extension.
	 *
	 * @var string
	 */
	protected string $shortSlug;

	/**
	 * Cached package metadata.
	 *
	 * @var ?array<string,string>
	 */
	protected ?array $packageMeta;
	/**
	 * Constructor.
	 *
	 * @param string                    $filePath Path to the plugin file.
	 * @param FileContentReaderContract $reader File content reader instance.
	 * @throws InvalidArgumentException If the file path is invalid.
	 */
	public function __construct( string $filePath, FileContentReaderContract $reader ) {
		if ( ! file_exists( $filePath ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- This is an exception message that is not displayed to end users
			throw new InvalidArgumentException( 'Invalid file path: ' . $filePath );
		}
		$this->filePath    = $filePath;
		$this->reader      = $reader;
		$basename          = basename( $filePath );
		$directory         = dirname( $filePath );
		$directoryBasename = pathinfo( $directory, PATHINFO_BASENAME );
		// Includes any .php extension.
		$this->fullSlug = $directoryBasename . '/' . $basename;
		// Remove extension (if any) to get just the filename.
		$this->shortSlug   = pathinfo( $basename, PATHINFO_FILENAME );
		$this->packageMeta = null;
	}
	/**
	 * Gets the name of the plugin.
	 *
	 * @return string The plugin name.
	 */
	public function getName(): string {
		return $this->getPackageMeta()['Name'];
	}
	/**
	 * Full slug, including any directory prefix and any file extension like .php - may contain a "/".
	 *
	 * @return string
	 */
	public function getFullSlug(): string {
		return $this->fullSlug;
	}
	/**
	 * Slug minus any prefix. Should not contain a "/".
	 *
	 * @return string
	 */
	public function getShortSlug(): string {
		return $this->shortSlug;
	}
	/**
	 * Gets the version of the plugin.
	 *
	 * @return ?string The plugin version or null if not available.
	 */
	public function getVersion(): ?string {
		return $this->getPackageMeta()['Version'] ?? null;
	}
	/**
	 * Gets the plugin URI.
	 *
	 * @return ?string The plugin URI or null if not available.
	 */
	public function getViewURL(): ?string {
		return $this->getPackageMeta()['PluginURI'] ?? null;
	}
	/**
	 * Gets the download URL for the plugin.
	 *
	 * @return ?string The plugin download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		return $this->getPackageMeta()['UpdateURI'] ?? null;
	}
	/**
	 * Gets the WordPress version the plugin has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		return null;
	}
	/**
	 * Gets the stable version of the plugin.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		return null;
	}
	/**
	 * Gets the plugin tags.
	 *
	 * @return string[] Array of plugin tags.
	 */
	public function getTags(): array {
		return [];
	}
	/**
	 * Gets the plugin author.
	 *
	 * @return ?string The plugin author or null if not available.
	 */
	public function getAuthor(): ?string {
		return $this->getPackageMeta()['Author'] ?? null;
	}
	/**
	 * Gets the plugin author's URL.
	 *
	 * @return ?string The plugin author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		return $this->getPackageMeta()['AuthorURI'] ?? null;
	}
	/**
	 * Gets the plugin license.
	 *
	 * @return ?string The plugin license or null if not available.
	 */
	public function getLicense(): ?string {
		return null;
	}
	/**
	 * Gets the plugin license URL.
	 *
	 * @return ?string The plugin license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		return null;
	}
	/**
	 * Gets the short description of the plugin.
	 *
	 * @return ?string The plugin short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		return $this->getPackageMeta()['Description'] ?? null;
	}
	/**
	 * Gets the full description of the plugin.
	 *
	 * @return ?string The plugin full description or null if not available.
	 */
	public function getDescription(): ?string {
		return null;
	}
	/**
	 * Gets the minimum WordPress version required by the plugin.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		return $this->getPackageMeta()['RequiresWP'] ?? null;
	}
	/**
	 * Gets the minimum PHP version required by the plugin.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		return $this->getPackageMeta()['RequiresPHP'] ?? null;
	}
	/**
	 * Gets the text domain used by the plugin for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		return $this->getPackageMeta()['TextDomain'] ?? null;
	}
	/**
	 * Gets the domain path for the plugin's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		return $this->getPackageMeta()['DomainPath'] ?? null;
	}
	/**
	 * Gets the list of plugins that this plugin requires.
	 *
	 * @return string[] Array of required plugin identifiers.
	 */
	public function getRequiresPlugins(): array {
		$meta = $this->getPackageMeta();
		return isset( $meta['RequiresPlugins'] )
			? array_map( 'trim', explode( ',', $meta['RequiresPlugins'] ) )
			: [];
	}
	/**
	 * Gets the sections of the plugin description.
	 *
	 * @return array<string,string> Associative array of section names and their content.
	 */
	public function getSections(): array {
		return [];
	}
	/**
	 * Determines if this plugin is a network-only plugin.
	 *
	 * @return boolean True if this is a network plugin, false otherwise.
	 */
	public function getNetwork(): bool {
		return (bool) ( $this->getPackageMeta()['Network'] ?? false );
	}

	/**
	 * Gets the plugin package metadata.
	 *
	 * Parses plugin file headers to extract metadata using a SelectHeadersPackageMetaParser.
	 * Result is cached for subsequent calls.
	 *
	 * @return array<string,string> Associative array of plugin metadata.
	 */
	protected function getPackageMeta(): array {
		if ( null !== $this->packageMeta ) {
			return $this->packageMeta;
		}
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
				// '_sitewide'       => 'Site Wide Only', // deprecated.
			)
		);
		$metaArray = $parser->parse( $this->reader->read( $this->filePath ) );
		Validator::create( new PluginHeadersArrayRule() )->check( $metaArray );
		$this->packageMeta = $metaArray;
		return $this->packageMeta;
	}
}
