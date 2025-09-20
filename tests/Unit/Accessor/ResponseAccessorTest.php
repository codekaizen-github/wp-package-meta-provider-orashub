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
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
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
		$options         = [];
		$statusCode      = 200;
		$reasonPhrase    = 'OK';
		$xFooHeaderKey   = 'X-Foo';
		$xFooHeaderValue = 'Bar';
		$headers         = [ $xFooHeaderKey => $xFooHeaderValue ];
		$body            = <<<'JSON'
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
		$handler         = new MockHandler(
			[
				new Response( $statusCode, $headers, $body, '1.1', $reasonPhrase ),
			]
		);
		$handlerStack    = HandlerStack::create( $handler );
		$client          = new Client( [ 'handler' => $handlerStack ] );
		$logger          = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' )->once()->with(
			"HTTP GET Request {$url}",
			[
				'uri'     => $url,
				'options' => $options,
			]
		);
		$logger->shouldReceive( 'debug' )->once()->with(
			"HTTP GET Response {$url} {$statusCode}",
			[
				'uri'          => $url,
				'options'      => $options,
				'statusCode'   => $statusCode,
				'reasonPhrase' => $reasonPhrase,
				'headers'      => [ $xFooHeaderKey => [ $xFooHeaderValue ] ],
				'body'         => $body,
			]
		);

		$accessor = new ResponseAccessor( $client, $url, $options, $logger );
		$value    = $accessor->get();
		$this->assertEquals( $statusCode, $value->getStatusCode() );
		$this->assertEquals( [ $xFooHeaderValue ], $value->getHeader( $xFooHeaderKey ) );
		$this->assertEquals( $body, $value->getBody() );
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
		$handler         = new MockHandler(
			[
				new Response( $statusCode, $headers, $response ),
			]
		);
		$handlerStack    = HandlerStack::create( $handler );
		$client          = new Client( [ 'handler' => $handlerStack ] );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( 'Invalid URL' );
		$accessor = new ResponseAccessor( $client, $url, [] );
	}
}
