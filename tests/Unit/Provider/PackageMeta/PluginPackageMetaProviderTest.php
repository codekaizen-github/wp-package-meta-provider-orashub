<?php
/**
 * Local Plugin Package Meta Provider Test
 *
 * Tests for the provider that reads and extracts metadata from local plugin files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderORASHub\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderORASHubTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the plugin package metadata provider implementation.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct plugin name from the My Basics plugin.
	 *
	 * @return void
	 */
	public function testGetNameFromPluginMyBasicsPlugin(): void {
		$filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
		$reader   = new FileContentReader();
		$provider = new PluginPackageMetaProvider( $filePath, $reader );
		$this->assertEquals( 'My Basics Plugin', $provider->getName() );
	}
}
