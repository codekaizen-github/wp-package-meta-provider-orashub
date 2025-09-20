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
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;

/**
 * Provider for local WordPress theme package metadata.
 *
 * Reads and parses metadata from theme files in the local filesystem.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProvider implements ThemePackageMetaContract {
	/**
	 * HTTP client.
	 *
	 * @var AssociativeArrayStringToMixedAccessorContract
	 */
	protected AssociativeArrayStringToMixedAccessorContract $client;

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
	 * @var ?array<string,mixed>
	 */
	protected ?array $packageMeta;
	/**
	 * Constructor.
	 *
	 * @param AssociativeArrayStringToMixedAccessorContract $client HTTP client.
	 */
	public function __construct( AssociativeArrayStringToMixedAccessorContract $client ) {

		$this->client      = $client;
		$this->packageMeta = null;
	}
	/**
	 * Gets the name of the plugin.
	 *
	 * @return string The plugin name.
	 */
	public function getName(): string {
		/**
		 * Value will have been validated.
		 *
		 * @var string $value
		 */
		$value = $this->getPackageMeta()['name'];
		return $value;
	}
	/**
	 * Full slug, including any directory prefix and any file extension like .php - may contain a "/".
	 *
	 * @return string
	 */
	public function getFullSlug(): string {
		/**
		 * Value will have been validated.
		 *
		 * @var string $value
		 */
		$value = $this->getPackageMeta()['fullSlug'];
		return $value;
	}
	/**
	 * Slug minus any prefix. Should not contain a "/".
	 *
	 * @return string
	 */
	public function getShortSlug(): string {
		/**
		 * Value will have been validated.
		 *
		 * @var string $value
		 */
		$value = $this->getPackageMeta()['shortSlug'];
		return $value;
	}
	/**
	 * Gets the plugin URI.
	 *
	 * @return ?string The plugin URI or null if not available.
	 */
	public function getViewURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['viewUrl'];
		return $value;
	}
	/**
	 * Gets the version of the plugin.
	 *
	 * @return ?string The plugin version or null if not available.
	 */
	public function getVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['version'];
		return $value;
	}
	/**
	 * Gets the download URL for the plugin.
	 *
	 * @return ?string The plugin download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['downloadUrl'];
		return $value;
	}
	/**
	 * Gets the WordPress version the plugin has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['tested'];
		return $value;
	}
	/**
	 * Gets the stable version of the plugin.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['stable'];
		return $value;
	}
	/**
	 * Gets the plugin tags.
	 *
	 * @return string[] Array of plugin tags.
	 */
	public function getTags(): array {
		/**
		 * Value will have been validated.
		 *
		 * @var string[] $value
		 */
		$value = $this->getPackageMeta()['tags'];
		return $value;
	}
	/**
	 * Gets the plugin author.
	 *
	 * @return ?string The plugin author or null if not available.
	 */
	public function getAuthor(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['author'];
		return $value;
	}
	/**
	 * Gets the plugin author's URL.
	 *
	 * @return ?string The plugin author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['authorUrl'];
		return $value;
	}
	/**
	 * Gets the plugin license.
	 *
	 * @return ?string The plugin license or null if not available.
	 */
	public function getLicense(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['license'];
		return $value;
	}
	/**
	 * Gets the plugin license URL.
	 *
	 * @return ?string The plugin license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['licenseUrl'];
		return $value;
	}
	/**
	 * Gets the short description of the plugin.
	 *
	 * @return ?string The plugin short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['shortDescription'];
		return $value;
	}
	/**
	 * Gets the full description of the plugin.
	 *
	 * @return ?string The plugin full description or null if not available.
	 */
	public function getDescription(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string$value
		 */
		$value = $this->getPackageMeta()['description'];
		return $value;
	}
	/**
	 * Gets the minimum WordPress version required by the plugin.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['requiresWordPressVersion'];
		return $value;
	}
	/**
	 * Gets the minimum PHP version required by the plugin.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['requiresPHPVersion'];
		return $value;
	}
	/**
	 * Gets the text domain used by the plugin for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['textDomain'];
		return $value;
	}
	/**
	 * Gets the domain path for the plugin's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['domainPath'];
		return $value;
	}
	/**
	 * Gets the template for the theme.
	 *
	 * @return ?string The template or null if not specified.
	 */
	public function getTemplate(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['template'];
		return $value;
	}
	/**
	 * Gets the status for the theme.
	 *
	 * @return ?string The status or null if not specified.
	 */
	public function getStatus(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['status'];
		return $value;
	}
	/**
	 * Gets the theme package metadata.
	 *
	 * Parses theme file headers to extract metadata using a SelectHeadersPackageMetaParser.
	 * Result is cached for subsequent calls.
	 *
	 * @return array<string,mixed> Associative array of theme metadata.
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
		 * @var array<string,mixed> $metaArray
		 * */
		$this->packageMeta = $metaArray;
		return $this->packageMeta;
	}
	/**
	 * Undocumented function
	 *
	 * @return mixed
	 */
	public function jsonSerialize(): mixed {
		return [
			'name'                     => $this->getName(),
			'fullSlug'                 => $this->getFullSlug(),
			'shortSlug'                => $this->getShortSlug(),
			'viewUrl'                  => $this->getViewUrl(),
			'version'                  => $this->getVersion(),
			'downloadUrl'              => $this->getDownloadUrl(),
			'tested'                   => $this->getTested(),
			'stable'                   => $this->getStable(),
			'tags'                     => $this->getTags(),
			'author'                   => $this->getAuthor(),
			'authorUrl'                => $this->getAuthorUrl(),
			'license'                  => $this->getLicense(),
			'licenseUrl'               => $this->getLicenseUrl(),
			'description'              => $this->getDescription(),
			'shortDescription'         => $this->getShortDescription(),
			'requiresWordPressVersion' => $this->getRequiresWordPressVersion(),
			'requiresPHPVersion'       => $this->getRequiresPHPVersion(),
			'textDomain'               => $this->getTextDomain(),
			'domainPath'               => $this->getDomainPath(),
			'template'                 => $this->getTemplate(),
			'status'                   => $this->getStatus(),
		];
	}
}
