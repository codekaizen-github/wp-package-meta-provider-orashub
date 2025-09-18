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

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\ThemePackageMetaProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the theme package metadata provider implementation.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct plugin name from the My Basics plugin.
	 *
	 * @return void
	 */
	public function testGetNameFromThemeFabledSunset(): void {
		$response                  = [
			'name'                     => 'Test Theme',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https://codekaizen.net',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		$metaAnnotationKeyAccessor = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$provider = new ThemePackageMetaProvider( $metaAnnotationKeyAccessor );
		$this->assertEquals( 'Test Theme', $provider->getName() );
	}
}
