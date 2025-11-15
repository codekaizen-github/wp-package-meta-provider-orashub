<?php
/**
 * Response Package Meta Array Assembler Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Tests\Unit\Assembler\Array\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Tests\Unit\Assembler\Array\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Array\PackageMeta\ResponsePackageMetaArrayAssembler;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Utils;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use StreamBucket;
use UnexpectedValueException;

/**
 * @covers \CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Array\PackageMeta\ResponsePackageMetaArrayAssembler
 */
class ResponsePackageMetaArrayAssemblerTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleReturnsDecodedMetaArrayOnValidResponse(): void {
		$metaKey         = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$metaArray       = [
			'foo' => 'bar',
			'baz' => 123,
		];
		$metaRaw         = json_encode( $metaArray );
		$responseDecoded = [
			'annotations' => [
				$metaKey => $metaRaw,
			],
		];
		$responseBody    = json_encode( $responseDecoded );

		$response = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );

		$assembler = new ResponsePackageMetaArrayAssembler( $metaKey );
		$result    = $assembler->assemble( $response );
		$this->assertSame( $metaArray, $result );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnInvalidJsonResponse(): void {
		$response = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( 'not-json' ) );
		$assembler = new ResponsePackageMetaArrayAssembler();
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnMissingAnnotationsKey(): void {
		$responseDecoded = [ 'not_annotations' => [] ];
		$responseBody    = json_encode( $responseDecoded );
		$response        = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new ResponsePackageMetaArrayAssembler();
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnMissingMetaAnnotationKey(): void {
		$metaKey         = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$responseDecoded = [
			'annotations' => [],
		];
		$responseBody    = json_encode( $responseDecoded );
		$response        = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new ResponsePackageMetaArrayAssembler( $metaKey );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnInvalidMetaJson(): void {
		$metaKey         = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$responseDecoded = [
			'annotations' => [
				$metaKey => 'not-json',
			],
		];
		$responseBody    = json_encode( $responseDecoded );
		$response        = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new ResponsePackageMetaArrayAssembler( $metaKey );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnMetaDecodedNotArray(): void {
		$metaKey         = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$responseDecoded = [
			'annotations' => [
				$metaKey => json_encode( 'not-an-array' ),
			],
		];
		$responseBody    = json_encode( $responseDecoded );
		$response        = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new ResponsePackageMetaArrayAssembler( $metaKey );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testLoggerIsCalledOnValidationFailure(): void {
		$metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$logger  = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'error' )
			->once();
		$responseDecoded = [ 'annotations' => [] ];
		$responseBody    = json_encode( $responseDecoded );
		$response        = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new ResponsePackageMetaArrayAssembler( $metaKey, $logger );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
}
