<?php
/**
 * Local Theme Package Meta Provider Factory
 *
 * Factory for creating local theme package meta providers.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

/**
 * Factory for creating local theme package meta providers.
 *
 * @since 1.0.0
 */
class LocalThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {
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
	 * Creates a new LocalThemePackageMetaProvider instance.
	 *
	 * @return ThemePackageMetaContract
	 */
	public function create(): ThemePackageMetaContract {
		return new LocalThemePackageMetaProvider(
			$this->filePath,
			new FileContentReader()
		);
	}
}
