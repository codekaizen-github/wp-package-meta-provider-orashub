<?php
/**
 * Test.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\Value\PackageMeta\Theme;

// phpcs:disable Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueService;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Response\MixedArrayResponseAssemblerContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Mockery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use Mockery\MockInterface;

// phpcs:enable Generic.Files.LineLength
/**
 * Undocumented class
 */
class StandardThemePackageMetaValueServiceTest extends TestCase {

	/**
	 * Undocumented variable
	 *
	 * @var (RequestInterface&MockInterface)|null
	 */
	protected ?RequestInterface $request;

	/**
	 * Undocumented variable
	 *
	 * @var (ResponseInterface&MockInterface)|null
	 */
	protected ?ResponseInterface $response;

	/**
	 * Undocumented variable
	 *
	 * @var (ClientInterface&MockInterface)|null
	 */
	protected ?ClientInterface $client;

	/**
	 * Undocumented variable
	 *
	 * @var (MixedArrayResponseAssemblerContract&MockInterface)|null
	 */
	protected ?MixedArrayResponseAssemblerContract $assembler;

	/**
	 * Undocumented variable
	 *
	 * @var (LoggerInterface&MockInterface)|null
	 */
	protected ?LoggerInterface $logger;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function setUp(): void {
		$this->logger    = Mockery::mock( LoggerInterface::class );
		$this->request   = Mockery::mock( RequestInterface::class );
		$this->response  = Mockery::mock( ResponseInterface::class );
		$this->client    = Mockery::mock( ClientInterface::class );
		$this->assembler = Mockery::mock( MixedArrayResponseAssemblerContract::class );
		$this
			->getRequest()
			->shouldReceive( 'getMethod' )
			->byDefault()
			->andReturn( 'GET' );
		$this
			->getRequest()
			->shouldReceive( 'getUri' )
			->byDefault()
			->andReturn( new Uri( 'http://example.com' ) );
		$this
			->getClient()
			->shouldReceive( 'sendRequest' )
			->byDefault()
			->with( $this->getRequest() )
			->andReturn( $this->getResponse() );
		$this
			->getResponse()
			->shouldReceive( 'getStatusCode' )
			->byDefault()
			->andReturn( 200 );
		$this
			->getResponse()
			->shouldReceive( 'getReasonPhrase' )
			->byDefault()
			->andReturn( 'OK' );
		$this
			->getResponse()
			->shouldReceive( 'getHeaders' )
			->byDefault()
			->andReturn( [] );
		$metaKey             = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata';
		$metaValue           = [
			'name'                     => 'Test Theme',
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'textDomain'               => 'test-theme',
			'domainPath'               => '/languages',
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'default',
			'status'                   => 'publish',
		];
		$responseBodyDecoded = [
			'annotations' => [
				$metaKey => $metaValue,
			],
		];
		// phpcs:disable WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$this
			->getResponse()
			->shouldReceive( 'getBody' )
			->byDefault()
			->andReturn(
				Utils::streamFor(
					json_encode(
						$responseBodyDecoded
					)
				)
			);
		// phpcs:enable WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$this->assembler
			->shouldReceive( 'assemble' )
			->byDefault()
			->with( $this->getResponse() )
			->andReturn( $metaValue );
		$this->logger->shouldReceive( 'debug' )->byDefault();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
	}

	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface&MockInterface
	 */
	protected function getLogger(): LoggerInterface&MockInterface {
		self::assertNotNull( $this->logger );
		return $this->logger;
	}

	/**
	 * Undocumented function
	 *
	 * @return RequestInterface&MockInterface
	 */
	protected function getRequest(): RequestInterface&MockInterface {
		self::assertNotNull( $this->request );
		return $this->request;
	}

	/**
	 * Undocumented function
	 *
	 * @return ResponseInterface&MockInterface
	 */
	protected function getResponse(): ResponseInterface&MockInterface {
		self::assertNotNull( $this->response );
		return $this->response;
	}

	/**
	 * Undocumented function
	 *
	 * @return ClientInterface&MockInterface
	 */
	protected function getClient(): ClientInterface&MockInterface {
		self::assertNotNull( $this->client );
		return $this->client;
	}

	/**
	 * Undocumented function
	 *
	 * @return MixedArrayResponseAssemblerContract&MockInterface
	 */
	protected function getAssembler(): MixedArrayResponseAssemblerContract {
		self::assertNotNull( $this->assembler );
		return $this->assembler;
	}

	/**
	 * Test getPackageMeta returns value on success.
	 */
	public function testGetPackageMetaReturnsValueOnSuccess(): void {
		$sut = new StandardThemePackageMetaValueService(
			$this->getRequest(),
			$this->getClient(),
			$this->getAssembler()
		);
		$this->assertInstanceOf( ThemePackageMetaValueContract::class, $sut->getPackageMeta() );
	}


	/**
	 * Test getPackageMeta does not cache the value.
	 */
	public function testGetPackageMetaDoesNotCacheValue(): void {
		$sut    = new StandardThemePackageMetaValueService(
			$this->getRequest(),
			$this->getClient(),
			$this->getAssembler()
		);
		$first  = $sut->getPackageMeta();
		$second = $sut->getPackageMeta();
		$this->assertNotSame( $first, $second );
	}


	/**
	 * Test getPackageMeta throws on assembler exception.
	 */
	public function testGetPackageMetaThrowsOnAssemblerException(): void {
		$this->expectException( UnexpectedValueException::class );
		$this
			->getAssembler()
			->shouldReceive( 'assemble' )
			->with( $this->getResponse() )
			->andThrow( new UnexpectedValueException( 'Invalid meta' ) );
		$sut = new StandardThemePackageMetaValueService(
			$this->getRequest(),
			$this->getClient(),
			$this->getAssembler()
		);
		$sut->getPackageMeta();
	}
}
