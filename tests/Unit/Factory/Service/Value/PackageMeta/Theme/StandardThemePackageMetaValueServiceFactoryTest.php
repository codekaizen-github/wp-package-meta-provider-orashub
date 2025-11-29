<?php
/**
 * Factory for StandardThemePackageMetaValueService instances.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Service\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Service\Value\PackageMeta\Theme;

// phpcs:disable Generic.Files.LineLength
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Mockery;
use Mockery\MockInterface;

/**
 * Undocumented class
 */
class StandardThemePackageMetaValueServiceFactoryTest extends TestCase {

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $assembler;

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $client;

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $request;

	/**
	 * Undocumented variable
	 *
	 * @var ?MockInterface
	 */
	protected ?MockInterface $service;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function setUp(): void {
		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->assembler = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Response\MixedArray\PackageMetaMixedArrayResponseAssembler'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$this->client  = Mockery::mock( 'overload:GuzzleHttp\Client' );
		$this->request = Mockery::mock( 'overload:GuzzleHttp\Psr7\Request' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->service = Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueService',
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getAssembler(): MockInterface {
		self::assertNotNull( $this->assembler );
		return $this->assembler;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getClient(): MockInterface {
		self::assertNotNull( $this->client );
		return $this->client;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getRequest(): MockInterface {
		self::assertNotNull( $this->request );
		return $this->request;
	}

	/**
	 * Undocumented function
	 *
	 * @return MockInterface
	 */
	public function getService(): MockInterface {
		self::assertNotNull( $this->service );
		return $this->service;
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testCreateReturnsServiceInstanceWithDefaults() {
		$sut     = new StandardThemePackageMetaValueServiceFactory( 'http://example.com/meta.json' );
		$service = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $service );
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testCreateReturnsServiceInstanceWithCustomLogger() {
		$logger  = Mockery::mock( LoggerInterface::class );
		$sut     = new StandardThemePackageMetaValueServiceFactory(
			'http://example.com/meta.json',
			'custom-key',
			[],
			$logger
		);
		$service = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $service );
	}

	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testCreateUsesCustomMetaAnnotationKey() {
		$metaKey = 'custom-meta-key';
		$this->getAssembler()->shouldReceive( '__construct' )
			->with( $metaKey, Mockery::type( LoggerInterface::class ) )
			->andReturnNull();
		$sut     = new StandardThemePackageMetaValueServiceFactory( 'http://example.com/meta.json', $metaKey );
		$service = $sut->create();
		$this->assertInstanceOf( ThemePackageMetaValueServiceContract::class, $service );
	}
}
