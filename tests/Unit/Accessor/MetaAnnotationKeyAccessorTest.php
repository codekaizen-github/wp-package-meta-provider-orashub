<?php
/**
 * Test for MetaAnnotationKeyAccessor
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class MetaAnnotationKeyAccessorTest extends TestCase {
	/**
	 * Undocumented variable
	 *
	 * @var (LoggerInterface&MockInterface)|null
	 */
	protected ?LoggerInterface $logger;
	/**
	 * Undocumented variable
	 *
	 * @var (MixedAccessorContract&MockInterface)|null
	 */
	protected ?MixedAccessorContract $client;
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->logger = Mockery::mock( LoggerInterface::class );
		$this->client = Mockery::mock( MixedAccessorContract::class );
	}
	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface&MockInterface
	 */
	protected function getLogger(): LoggerInterface {
		self::assertNotNull( $this->logger );
		return $this->logger;
	}
	/**
	 * Undocumented function
	 *
	 * @return MixedAccessorContract&MockInterface
	 */
	protected function getClient(): MixedAccessorContract {
		self::assertNotNull( $this->client );
		return $this->client;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsAndDataIsValid() {
		$expected = [
			'name'                     => 'Test Plugin',
			'fullSlug'                 => 'test-plugin/test-plugin.php',
			'shortSlug'                => 'test-plugin',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https:// codekaizen.net',
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
		// phpcs:disable WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Temporary disable sniff.
		$response = [
			'randomData'  => 'value',
			'otherData'   => 'value',
			'annotations' => [
				'otherDataOne'          => 1,
				'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
				'testMetaAnnotationKey' => json_encode( $expected ),
			],
		];
		// phpcs:enable WordPress.WP.AlternativeFunctions.json_encode_json_encode -- Temporary disable sniff.
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->assertEquals( $expected, $sut->get() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsAndDataIsNotJSONAndIsInvalid() {
		$expected = [
			'name'                     => 'Test Plugin',
			'fullSlug'                 => 'test-plugin/test-plugin.php',
			'shortSlug'                => 'test-plugin',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https:// codekaizen.net',
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
		$response = [
			'randomData'  => 'value',
			'otherData'   => 'value',
			'annotations' => [
				'otherDataOne'          => 1,
				'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
				'testMetaAnnotationKey' => $expected,
			],
		];
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testInputIsNotArrayAndThrowsException() {
		$response = 'hi';
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAnnotationsKeyDoesNotExistAndThrowsException() {
		$response = [
			'otherDataOne' => 1,
			'otherDataTwo' => [ 'asdf', 'fda', [ 'asdf' ] ],
		];
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testMetaKeyDoesNotExistAndThrowsException() {
		$response = [
			'otherDataOne' => 1,
			'otherDataTwo' => [ 'asdf', 'fda', [ 'asdf' ] ],
			'annotations'  => [
				'otherDataOne' => 1,
				'otherDataTwo' => [ 'asdf', 'fda', [ 'asdf' ] ],
			],
		];
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsButDataTypeIsInvalidScalarAndThrowsException() {
		$response = [
			'randomData'  => 'value',
			'otherData'   => 'value',
			'annotations' => [
				'otherDataOne'          => 1,
				'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
				'testMetaAnnotationKey' => 'hi',
			],
		];
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsButDataTypeIsInvalidIndexedArrayAndThrowsException() {
		$response = [
			'randomData'  => 'value',
			'otherData'   => 'value',
			'annotations' => [
				'otherDataOne'          => 1,
				'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
				'testMetaAnnotationKey' => [ 'hello', 'world' ],
			],
		];
		$this->getClient()->shouldReceive( 'get' )->with()->andReturn( $response );
		$this->getLogger()->shouldReceive( 'error' );
		$sut = new MetaAnnotationKeyAccessor(
			$this->getClient(),
			'testMetaAnnotationKey',
			$this->getLogger()
		);
		$this->expectException( UnexpectedValueException::class );
		$sut->get();
	}
}
