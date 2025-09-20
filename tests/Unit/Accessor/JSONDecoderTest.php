<?php
/**
 * Unit tests for JSONDecoder
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\JSONDecoder;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\StreamAccessorContract;
use Mockery;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Utils;

/**
 * Undocumented class
 */
class JSONDecoderTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testValidJSONIsValid() {
		$expected       = [
			'name'                     => 'Test Plugin',
			'fullSlug'                 => 'test-plugin/test-plugin.php',
			'shortSlug'                => 'test-plugin',
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
		$input          = <<<'JSON'
		{
			"name": "Test Plugin",
			"fullSlug": "test-plugin/test-plugin.php",
			"shortSlug": "test-plugin",
			"version": "3.0.1",
			"viewUrl": "https://codekaizen.net",
			"downloadUrl": "https://codekaizen.net",
			"tested": "6.8.2",
			"stable": "6.8.2",
			"tags": ["tag1", "tag2", "tag3"],
			"author": "Andrew Dawes",
			"authorUrl": "https://codekaizen.net/team/andrew-dawes",
			"license": "GPL v2 or later",
			"licenseUrl": "https://www.gnu.org/licenses/gpl-2.0.html",
			"description": "This is a test plugin",
			"shortDescription": "Test",
			"requiresWordPressVersion": "6.8.2",
			"requiresPHPVersion": "8.2.1",
			"textDomain": "test-plugin",
			"domainPath": "/languages",
			"requiresPlugins": ["akismet", "hello-dolly"],
			"sections": {"changelog": "changed", "about": "this is a plugin about section"},
			"network": true
		}
		JSON;
		$streamAccessor = Mockery::mock( StreamAccessorContract::class );
		$streamAccessor->shouldReceive( 'get' )->with()->andReturn( Utils::streamFor( $input ) );
		$decoder = new JSONDecoder( $streamAccessor );
		$this->assertEquals( $expected, $decoder->get() );
	}
		/**
		 * Undocumented function
		 *
		 * @return void
		 */
	public function testValidJSONDoesNotMatchExpectedAndIsInvalid() {
		$expected       = [
			'name'                     => 'Test Plugin',
			'fullSlug'                 => 'test-plugin/test-plugin.php',
			'shortSlug'                => 'test-plugin',
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
		$input          = <<<'JSON'
		{
			"name": "A Different Plugin",
			"fullSlug": "different-plugin/different-plugin.php",
			"shortSlug": "different-plugin",
			"version": "3.0.1",
			"viewUrl": "https://codekaizen.net",
			"downloadUrl": "https://codekaizen.net",
			"tested": "6.8.2",
			"stable": "6.8.2",
			"tags": ["tag1", "tag2", "tag3"],
			"author": "Andrew Dawes",
			"authorUrl": "https://codekaizen.net/team/andrew-dawes",
			"license": "GPL v2 or later",
			"licenseUrl": "https://www.gnu.org/licenses/gpl-2.0.html",
			"description": "This is a test plugin",
			"shortDescription": "Test",
			"requiresWordPressVersion": "6.8.2",
			"requiresPHPVersion": "8.2.1",
			"textDomain": "test-plugin",
			"domainPath": "/languages",
			"requiresPlugins": ["akismet", "hello-dolly"],
			"sections": {"changelog": "changed", "about": "this is a plugin about section"},
			"network": true
		}
		JSON;
		$streamAccessor = Mockery::mock( StreamAccessorContract::class );
		$streamAccessor->shouldReceive( 'get' )->with()->andReturn( Utils::streamFor( $input ) );
		$decoder = new JSONDecoder( $streamAccessor );
		$this->assertNotEquals( $expected, $decoder->get() );
	}
}
