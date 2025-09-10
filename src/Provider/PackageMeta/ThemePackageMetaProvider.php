<?php
/**
 * Local Theme Package Meta Provider
 *
 * Provides metadata for WordPress themes installed locally.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta;

use Respect\Validation\Validator;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta\ThemeHeadersArrayRule;
use InvalidArgumentException;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;

/**
 * Provider for local WordPress theme package metadata.
 *
 * Reads and parses metadata from theme files in the local filesystem.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProvider implements ThemePackageMetaContract {

	/**
	 * URL to meta endpoint.
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * Key to extract metadata from.
	 *
	 * @var string
	 */
	protected string $metaAnnotationKey;

	/**
	 * HTTP client.
	 *
	 * @var MixedAccessorContract
	 */
	protected MixedAccessorContract $client;

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
	 * @param MixedAccessorContract $client HTTP client.
	 * @throws InvalidArgumentException If the URL is invalid.
	 */
	public function __construct( MixedAccessorContract $client ) {

		$this->client      = $client;
		$this->packageMeta = null;
	}
	/**
	 * Gets the name of the theme.
	 *
	 * @return string The theme name.
	 */
	public function getName(): string {
		return $this->getPackageMeta()['Name'];
	}
	/**
	 * Gets the full slug, including any directory prefix and file extension.
	 *
	 * @return string The full slug.
	 */
	public function getFullSlug(): string {
		return $this->fullSlug;
	}
	/**
	 * Gets the short slug, minus any prefix. Should not contain a "/".
	 *
	 * @return string The short slug.
	 */
	public function getShortSlug(): string {
		return $this->shortSlug;
	}
	/**
	 * Gets the version of the theme.
	 *
	 * @return ?string The theme version or null if not available.
	 */
	public function getVersion(): ?string {
		return $this->getPackageMeta()['Version'] ?? null;
	}
	/**
	 * Gets the theme URI.
	 *
	 * @return ?string The theme URI or null if not available.
	 */
	public function getViewURL(): ?string {
		return $this->getPackageMeta()['ThemeURI'] ?? null;
	}
	/**
	 * Gets the download URL for the theme.
	 *
	 * @return ?string The theme download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		return $this->getPackageMeta()['UpdateURI'] ?? null;
	}
	/**
	 * Gets the WordPress version the theme has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		return null;
	}
	/**
	 * Gets the stable version of the theme.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		return null;
	}
	/**
	 * Gets the theme tags.
	 *
	 * @return string[] Array of theme tags.
	 */
	public function getTags(): array {
		return [];
	}
	/**
	 * Gets the theme author.
	 *
	 * @return ?string The theme author or null if not available.
	 */
	public function getAuthor(): ?string {
		return $this->getPackageMeta()['Author'] ?? null;
	}
	/**
	 * Gets the theme author's URL.
	 *
	 * @return ?string The theme author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		return $this->getPackageMeta()['AuthorURI'] ?? null;
	}
	/**
	 * Gets the theme license.
	 *
	 * @return ?string The theme license or null if not available.
	 */
	public function getLicense(): ?string {
		return null;
	}
	/**
	 * Gets the theme license URL.
	 *
	 * @return ?string The theme license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		return null;
	}
	/**
	 * Gets the short description of the theme.
	 *
	 * @return ?string The theme short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		return $this->getPackageMeta()['Description'] ?? null;
	}
	/**
	 * Gets the full description of the theme.
	 *
	 * @return ?string The theme full description or null if not available.
	 */
	public function getDescription(): ?string {
		return null;
	}
	/**
	 * Gets the minimum WordPress version required by the theme.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		return $this->getPackageMeta()['RequiresWP'] ?? null;
	}
	/**
	 * Gets the minimum PHP version required by the theme.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		return $this->getPackageMeta()['RequiresPHP'] ?? null;
	}
	/**
	 * Gets the text domain used by the theme for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		return $this->getPackageMeta()['TextDomain'] ?? null;
	}
	/**
	 * Gets the domain path for the theme's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		return $this->getPackageMeta()['DomainPath'] ?? null;
	}
	/**
	 * Gets the template for the theme.
	 *
	 * @return ?string The template or null if not specified.
	 */
	public function getTemplate(): ?string {
		return $this->getPackageMeta()['Template'] ?? null;
	}
	/**
	 * Gets the status for the theme.
	 *
	 * @return ?string The status or null if not specified.
	 */
	public function getStatus(): ?string {
		return $this->getPackageMeta()['Status'] ?? null;
	}
	/**
	 * Gets the theme package metadata.
	 *
	 * Parses theme file headers to extract metadata using a SelectHeadersPackageMetaParser.
	 * Result is cached for subsequent calls.
	 *
	 * @return array<string,string> Associative array of theme metadata.
	 */
	protected function getPackageMeta(): array {
		if ( null !== $this->packageMeta ) {
			return $this->packageMeta;
		}
		$metaArray = $this->client->get();
		Validator::create( new ThemeHeadersArrayRule() )->check( $metaArray );
		/**
		 * Meta array will have been validated.
		 *
		 * @var array<string,string> $metaArray
		 * */
		$this->packageMeta = $metaArray;
		return $this->packageMeta;
	}
}
