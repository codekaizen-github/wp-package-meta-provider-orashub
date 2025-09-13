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
		$response                  = [
			'name'                     => 'Test Plugin',
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
			'description'              => 'This is a test plugin',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'requiresPlugins'          => [ 'akismet', 'hello-dolly' ],
			'sections'                 => [
				'changelog' => 'changed',
				'about'     => 'this is a plugin about section',
			],
			'network'                  => true,
		];
		$metaAnnotationKeyAccessor = Mockery::mock( MetaAnnotationKeyAccessor::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$provider = new PluginPackageMetaProvider( $metaAnnotationKeyAccessor );
		$this->assertEquals( 'Test Plugin', $provider->getName() );
	}
}
