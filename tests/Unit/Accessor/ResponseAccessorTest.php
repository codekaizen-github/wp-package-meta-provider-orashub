<?php
/**
 * Test for ResponseAccessor
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\ResponseAccessor;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class ResponseAccessorTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testIsValid(): void {
		$url             = 'https://codekaizen.net';
		$statusCode      = 200;
		$xFooHeaderKey   = 'X-Foo';
		$xFooHeaderValue = 'Bar';
		$headers         = [ $xFooHeaderKey => $xFooHeaderValue ];
		$response        = <<<'JSON'
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
		$mock            = new MockHandler(
			[
				new Response( $statusCode, $headers, $response ),
			]
		);
		$handlerStack    = HandlerStack::create( $mock );
		$client          = new Client( [ 'handler' => $handlerStack ] );
		$accessor        = new ResponseAccessor( $client, $url, [] );
		$value           = $accessor->get();
		$this->assertEquals( $statusCode, $value->getStatusCode() );
		$this->assertEquals( [ $xFooHeaderValue ], $value->getHeader( $xFooHeaderKey ) );
		$this->assertEquals( $response, $value->getBody() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testUrlIsInvalidAndThrowsException(): void {
		$url             = 'not_a_url';
		$statusCode      = 200;
		$xFooHeaderKey   = 'X-Foo';
		$xFooHeaderValue = 'Bar';
		$headers         = [ $xFooHeaderKey => $xFooHeaderValue ];
		$response        = <<<'JSON'
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
		$mock            = new MockHandler(
			[
				new Response( $statusCode, $headers, $response ),
			]
		);
		$handlerStack    = HandlerStack::create( $mock );
		$client          = new Client( [ 'handler' => $handlerStack ] );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Invalid URL' );
		$accessor = new ResponseAccessor( $client, $url, [] );
	}
}
