<?php
/**
 * Local Theme Package Meta Provider Test
 *
 * Tests for the provider that reads and extracts metadata from local theme files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\LocalThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderORASHub\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderORASHubTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the theme package metadata provider implementation.
 *
 * @since 1.0.0
 */
class LocalThemePackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct theme name from the Fabled Sunset theme.
	 *
	 * @return void
	 */
	public function testGetNameFromThemeFabledSunset(): void {
		$filePath = FixturePathHelper::getPathForTheme() . '/fabled-sunset/style.css';
		$reader   = new FileContentReader();
		$provider = new LocalThemePackageMetaProvider( $filePath, $reader );
		$this->assertEquals( 'Fabled Sunset', $provider->getName() );
	}
}
