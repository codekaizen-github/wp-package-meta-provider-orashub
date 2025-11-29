<?php
/**
 * Response Package Meta Array Assembler Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Assembler\Response\MixedArray
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Assembler\Response\MixedArray;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Response\MixedArray\PackageMetaMixedArrayResponseAssembler;
use GuzzleHttp\Psr7\Utils;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class PackageMetaMixedArrayResponseAssemblerTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleReturnsDecodedMetaArrayOnValidResponse(): void {
		$metaKey   = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$metaArray = [
			'foo' => 'bar',
			'baz' => 123,
		];
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$metaRaw         = json_encode( $metaArray );
		$responseDecoded = [
			'annotations' => [
				$metaKey => $metaRaw,
			],
		];
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseBody = json_encode( $responseDecoded );

		$response = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );

		$assembler = new PackageMetaMixedArrayResponseAssembler( $metaKey );
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
		$assembler = new PackageMetaMixedArrayResponseAssembler();
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
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseBody = json_encode( $responseDecoded );
		$response     = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new PackageMetaMixedArrayResponseAssembler();
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
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseBody = json_encode( $responseDecoded );
		$response     = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new PackageMetaMixedArrayResponseAssembler( $metaKey );
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
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseBody = json_encode( $responseDecoded );
		$response     = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new PackageMetaMixedArrayResponseAssembler( $metaKey );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAssembleThrowsOnMetaDecodedNotArray(): void {
		$metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		// phpcs:disable WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseDecoded = [
			'annotations' => [
				$metaKey => json_encode( 'not-an-array' ),
			],
		];
		$responseBody    = json_encode( $responseDecoded );
		// phpcs:enable WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$response = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new PackageMetaMixedArrayResponseAssembler( $metaKey );
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
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$responseBody = json_encode( $responseDecoded );
		$response     = Mockery::mock( ResponseInterface::class );
		$response->shouldReceive( 'getBody' )->andReturn( Utils::streamFor( $responseBody ) );
		$assembler = new PackageMetaMixedArrayResponseAssembler( $metaKey, $logger );
		$this->expectException( UnexpectedValueException::class );
		$assembler->assemble( $response );
	}
}
