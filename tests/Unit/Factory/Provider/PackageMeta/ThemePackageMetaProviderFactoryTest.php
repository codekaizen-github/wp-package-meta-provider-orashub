<?php
/**
 * Local Theme Package Meta Provider Factory Test
 *
 * Tests for the factory that creates local theme package metadata providers.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderORASHubTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the theme package metadata provider factory implementation.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderFactoryTest extends TestCase {

	/**
	 * Tests factory creates a provider that correctly extracts name from the Fabled Sunset theme.
	 *
	 * @return void
	 */
	public function testThemePackageMetaFabledSunsetThemeGetName(): void {
		$filePath = FixturePathHelper::getPathForTheme() . '/fabled-sunset/style.css';
		$factory  = new ThemePackageMetaProviderFactory( $filePath );
		$provider = $factory->create();
		$this->assertEquals( 'Fabled Sunset', $provider->getName() );
	}
}
