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

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
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
		// $url                       = 'https://example.com/my-plugin/';
		// $metaAnnotationKey         = 'metaAnnotationKey';
		// $client                    = new Client();
		// $requestor                 = new GuzzleHttpGetRequest( $client, $url );
		// $metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $requestor, $metaAnnotationKey );
		$response                  =
		[
			'FabledSunset' => 'Fabled Sunset',
			'ThemeURI'     => ' https://example.com/fabled-sunset',
			'Description'  => 'Custom theme description...',
			'Version'      => '1.0.0',
			'RequiresWP'   => '5.2',
			'RequiresPHP'  => '7.2',
			'Author'       => 'Your Name',
			'AuthorURI'    => 'https://example.com',
			'TextDomain'   => 'fabled-sunset',
			'License'      => 'GPL v2 or later',
			'LicenseURI'   => 'http://www.gnu.org/licenses/gpl-2.0.txt',
			'UpdateURI'    => 'https://example.com/abledfabled-sunset/',
		];
		$metaAnnotationKeyAccessor = Mockery::mock( MetaAnnotationKeyAccessor::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$provider = new ThemePackageMetaProvider( $metaAnnotationKeyAccessor );
		$this->assertEquals( 'Fabled Sunset', $provider->getName() );
	}
}
