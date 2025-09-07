<?php
/**
 * Local Plugin Package Meta Provider Test
 *
 * Tests for the provider that reads and extracts metadata from local plugin files.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalPluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the plugin package metadata provider implementation.
 *
 * @since 1.0.0
 */
class LocalPluginPackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct plugin name from the My Basics plugin.
	 *
	 * @return void
	 */
	public function testGetNameFromPluginMyBasicsPlugin(): void {
		$filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
		$reader   = new FileContentReader();
		$provider = new LocalPluginPackageMetaProvider( $filePath, $reader );
		$this->assertEquals( 'My Basics Plugin', $provider->getName() );
	}
}
