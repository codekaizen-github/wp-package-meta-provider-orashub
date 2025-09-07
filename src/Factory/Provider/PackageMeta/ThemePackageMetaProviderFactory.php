<?php
/**
 * Local Theme Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\ThemePackageMetaProvider;

/**
 * Factory for creating local theme package meta providers.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {
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
	 * Constructor.
	 *
	 * @param string $url Endpoint with meta information.
	 * @param string $metaAnnotationKey Key to extract meta information from.
	 */
	public function __construct( string $url, string $metaAnnotationKey ) {
		$this->url               = $url;
		$this->metaAnnotationKey = $metaAnnotationKey;
	}

	/**
	 * Creates a new ThemePackageMetaProvider instance.
	 *
	 * @return ThemePackageMetaContract
	 */
	public function create(): ThemePackageMetaContract {
		return new ThemePackageMetaProvider(
			$this->url,
			$this->metaAnnotationKey
		);
	}
}
