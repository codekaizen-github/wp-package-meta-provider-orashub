<?php
/**
 * Local Theme Package Meta Provider
 *
 * Provides metadata for WordPress themes installed locally.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Value\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Value\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use Respect\Validation\Validator;
use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta\ThemePackageMetaRule;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use UnexpectedValueException;

/**
 * Provider for local WordPress theme package metadata.
 *
 * Reads and parses metadata from theme files in the local filesystem.
 *
 * @since 1.0.0
 */
class ThemePackageMetaValue implements ThemePackageMetaValueContract {

	/**
	 * Logger.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Cached package metadata.
	 *
	 * @var array<string,mixed>
	 */
	protected array $packageMeta;
	/**
	 * Constructor.
	 *
	 * @param array<string,mixed> $packageMeta HTTP packageMeta.
	 * @param LoggerInterface     $logger Logger.
	 * @throws UnexpectedValueException If the metadata is invalid.
	 */
	public function __construct(
		array $packageMeta,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->logger = $logger;
		try {
			Validator::create( new ThemePackageMetaRule() )->check( $packageMeta );
		} catch ( Throwable $e ) {
			$this->logger->error(
				'Failed to validate theme metadata.',
				[
					'exception'   => $e,
					'packageMeta' => $packageMeta,
				]
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new UnexpectedValueException( 'Invalid theme metadata.', 0, $e );
		}
		$this->packageMeta = $packageMeta;
	}
	/**
	 * Gets the name of the theme.
	 *
	 * @return string The theme name.
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
	 * Gets the theme URI.
	 *
	 * @return ?string The theme URI or null if not available.
	 */
	public function getViewURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['viewUrl'] ?? null;
		return $value;
	}
	/**
	 * Gets the version of the theme.
	 *
	 * @return ?string The theme version or null if not available.
	 */
	public function getVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['version'] ?? null;
		return $value;
	}
	/**
	 * Gets the download URL for the theme.
	 *
	 * @return ?string The theme download URL or null if not available.
	 */
	public function getDownloadURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['downloadUrl'] ?? null;
		return $value;
	}
	/**
	 * Gets the WordPress version the theme has been tested with.
	 *
	 * @return ?string Tested WordPress version or null if not available.
	 */
	public function getTested(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['tested'] ?? null;
		return $value;
	}
	/**
	 * Gets the stable version of the theme.
	 *
	 * @return ?string The stable version or null if not available.
	 */
	public function getStable(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['stable'] ?? null;
		return $value;
	}
	/**
	 * Gets the theme tags.
	 *
	 * @return string[] Array of theme tags.
	 */
	public function getTags(): array {
		/**
		 * Value will have been validated.
		 *
		 * @var string[] $value
		 */
		$value = $this->getPackageMeta()['tags'] ?? [];
		return $value;
	}
	/**
	 * Gets the theme author.
	 *
	 * @return ?string The theme author or null if not available.
	 */
	public function getAuthor(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['author'] ?? null;
		return $value;
	}
	/**
	 * Gets the theme author's URL.
	 *
	 * @return ?string The theme author's URL or null if not available.
	 */
	public function getAuthorURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['authorUrl'] ?? null;
		return $value;
	}
	/**
	 * Gets the theme license.
	 *
	 * @return ?string The theme license or null if not available.
	 */
	public function getLicense(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['license'] ?? null;
		return $value;
	}
	/**
	 * Gets the theme license URL.
	 *
	 * @return ?string The theme license URL or null if not available.
	 */
	public function getLicenseURL(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['licenseUrl'] ?? null;
		return $value;
	}
	/**
	 * Gets the short description of the theme.
	 *
	 * @return ?string The theme short description or null if not available.
	 */
	public function getShortDescription(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['shortDescription'] ?? null;
		return $value;
	}
	/**
	 * Gets the full description of the theme.
	 *
	 * @return ?string The theme full description or null if not available.
	 */
	public function getDescription(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string$value
		 */
		$value = $this->getPackageMeta()['description'] ?? null;
		return $value;
	}
	/**
	 * Gets the minimum WordPress version required by the theme.
	 *
	 * @return ?string The required WordPress version or null if not specified.
	 */
	public function getRequiresWordPressVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['requiresWordPressVersion'] ?? null;
		return $value;
	}
	/**
	 * Gets the minimum PHP version required by the theme.
	 *
	 * @return ?string The required PHP version or null if not specified.
	 */
	public function getRequiresPHPVersion(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['requiresPHPVersion'] ?? null;
		return $value;
	}
	/**
	 * Gets the text domain used by the theme for internationalization.
	 *
	 * @return ?string The text domain or null if not specified.
	 */
	public function getTextDomain(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['textDomain'] ?? null;
		return $value;
	}
	/**
	 * Gets the domain path for the theme's translation files.
	 *
	 * @return ?string The domain path or null if not specified.
	 */
	public function getDomainPath(): ?string {
		/**
		 * Value will have been validated.
		 *
		 * @var ?string $value
		 */
		$value = $this->getPackageMeta()['domainPath'] ?? null;
		return $value;
	}
	/**
	 * Gets the icons for the theme.
	 *
	 * @return array<string,string> Associative array of icons.
	 */
	public function getIcons(): array {
		/**
		 * Value will have been validated.
		 *
		 * @var array<string,string> $value
		 */
		$value = $this->getPackageMeta()['icons'] ?? [];
		return $value;
	}
	/**
	 * Gets the banners for the theme.
	 *
	 * @return array<string,string> Associative array of banners.
	 */
	public function getBanners(): array {
		/**
		 * Value will have been validated.
		 *
		 * @var array<string,string> $value
		 */
		$value = $this->getPackageMeta()['banners'] ?? [];
		return $value;
	}
	/**
	 * Gets the banners for the theme in RTL languages.
	 *
	 * @return array<string,string> Associative array of RTL banners.
	 */
	public function getBannersRTL(): array {
		/**
		 * Value will have been validated.
		 *
		 * @var array<string,string> $value
		 */
		$value = $this->getPackageMeta()['bannersRtl'] ?? [];
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
		$value = $this->getPackageMeta()['template'] ?? null;
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
		$value = $this->getPackageMeta()['status'] ?? null;
		return $value;
	}
	/**
	 * Gets the theme package metadata.
	 *
	 * Parses theme file headers to extract metadata using a SelectHeadersPackageMetaParser.
	 * Result is cached for subsequent calls.
	 *
	 * @return array<string,mixed> Associative array of theme metadata.
	 * @throws UnexpectedValueException If the metadata is invalid.
	 */
	protected function getPackageMeta(): array {
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
			'icons'                    => $this->getIcons(),
			'banners'                  => $this->getBanners(),
			'bannersRtl'               => $this->getBannersRTL(),
			'template'                 => $this->getTemplate(),
			'status'                   => $this->getStatus(),
		];
	}
}
