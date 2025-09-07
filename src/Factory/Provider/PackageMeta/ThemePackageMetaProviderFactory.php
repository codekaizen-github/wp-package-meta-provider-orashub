<?php
/**
 * Local Theme Package Meta Provider Factory
 *
 * Factory for creating local theme package meta providers.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\ThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderORASHub\Reader\FileContentReader;

/**
 * Factory for creating local theme package meta providers.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {
	/**
	 * Path to the theme file.
	 *
	 * @var string
	 */
	public string $filePath;

	/**
	 * Constructor.
	 *
	 * @param string $filePath Path to the theme file.
	 */
	public function __construct( string $filePath ) {
		$this->filePath = $filePath;
	}

	/**
	 * Creates a new ThemePackageMetaProvider instance.
	 *
	 * @return ThemePackageMetaContract
	 */
	public function create(): ThemePackageMetaContract {
		return new ThemePackageMetaProvider(
			$this->filePath,
			new FileContentReader()
		);
	}
}
