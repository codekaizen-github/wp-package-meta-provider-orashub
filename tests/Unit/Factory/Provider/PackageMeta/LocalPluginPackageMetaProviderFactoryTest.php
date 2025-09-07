<?php
/**
 * Unit tests for LocalPluginPackageMetaProviderFactory.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\LocalPluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderORASHubTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Unit test class for LocalPluginPackageMetaProviderFactory.
 */
class LocalPluginPackageMetaProviderFactoryTest extends TestCase {


	/**
	 * Tests that the provider returns the correct name for My Basics Plugin.
	 */
	public function testPluginPackageMetaMyBasicsPluginGetName(): void {
		$filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
		$factory  = new LocalPluginPackageMetaProviderFactory( $filePath );
		$provider = $factory->create();
		$this->assertEquals( 'My Basics Plugin', $provider->getName() );
	}

	/**
	 * Tests that the provider returns the correct name for Minimum Headers Plugin.
	 */
	public function testPluginPackageMetaMinimumHeadersPluginGetName(): void {
		$filePath = FixturePathHelper::getPathForPlugin() . '/minimum-headers-plugin.php';
		$factory  = new LocalPluginPackageMetaProviderFactory( $filePath );
		$provider = $factory->create();
		$this->assertEquals( 'Minimum Headers Plugin', $provider->getName() );
	}
}
