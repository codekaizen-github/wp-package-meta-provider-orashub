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

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider;
use Mockery;
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
		// $url                       = 'https://example.com/my-plugin/';
		// $metaAnnotationKey         = 'metaAnnotationKey';
		// $client                    = new Client();
		// $requestor                 = new GuzzleHttpGetRequest( $client, $url );
		// $metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $requestor, $metaAnnotationKey );
		$response                        =
			[
				'Name'            => 'Plugin Name',
				'PluginURI'       => 'https://example.com/plugin-name',
				'Description'     => 'Description of the plugin.',
				'Version'         => '1.0.0',
				'RequiresWP'      => '5.2',
				'RequiresPHP'     => '7.2',
				'Author'          => 'Your Name',
				'AuthorURI'       => 'https://example.com',
				'TextDomain'      => 'plugin-slug',
				'License'         => 'GPL v2 or later',
				'LicenseURI'      => 'http://www.gnu.org/licenses/gpl-2.0.txt',
				'UpdateURI'       => 'https://example.com/my-plugin/',
				'RequiresPlugins' => 'my-plugin, yet-another-plugin',
			];
		$metaAnnotationKeyAccessorDouble = Mockery::mock( MetaAnnotationKeyAccessor::class );
		$metaAnnotationKeyAccessorDouble->shouldReceive( 'get' )->with()->andReturn( $response );
		$provider = new PluginPackageMetaProvider( $metaAnnotationKeyAccessorDouble );
		$this->assertEquals( 'Plugin Name', $provider->getName() );
	}
}
