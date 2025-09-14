<?php
/**
 * Test for ResponseAccessor
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\ResponseAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\StreamAccessor;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class StreamAccessorTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testIsValid(): void {
		$response         = <<<'JSON'
		{
			"name": "Test Plugin",
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
		$responseAccessor = Mockery::mock( ResponseAccessor::class );
		$responseAccessor->shouldReceive( 'get' )->with()->andReturn( new Response( 200, [], $response ) );
		$streamAccessor = new StreamAccessor( $responseAccessor );
		$this->assertEquals( $response, $streamAccessor->get() );
	}
		/**
		 * Undocumented function
		 *
		 * @return void
		 */
	public function testIsInvalid(): void {
		$response         = <<<'JSON'
		{
			"name": "A Different Test Plugin",
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
		$responseAccessor = Mockery::mock( ResponseAccessor::class );
		$responseAccessor->shouldReceive( 'get' )->with()->andReturn( new Response( 200, [], $response ) );
		$streamAccessor = new StreamAccessor( $responseAccessor );
		$this->assertEquals( $response, $streamAccessor->get() );
	}
}
