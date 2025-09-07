<?php
/**
 * Local Plugin Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalPluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class LocalPluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract {
	/**
	 * Path to the plugin file.
	 *
	 * @var string
	 */
	public string $filePath;

	/**
	 * Constructor.
	 *
	 * @param string $filePath Path to the plugin file.
	 */
	public function __construct( string $filePath ) {
		$this->filePath = $filePath;
	}

	/**
	 * Creates a new LocalPluginPackageMetaProvider instance.
	 *
	 * @return PluginPackageMetaContract
	 */
	public function create(): PluginPackageMetaContract {
		return new LocalPluginPackageMetaProvider(
			$this->filePath,
			new FileContentReader()
		);
	}
}
