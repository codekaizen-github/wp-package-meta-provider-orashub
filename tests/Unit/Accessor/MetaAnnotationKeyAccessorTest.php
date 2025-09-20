<?php
/**
 * Test for MetaAnnotationKeyAccessor
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;
use Mockery;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Undocumented class
 */
class MetaAnnotationKeyAccessorTest extends TestCase {
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
		$response = [
			'otherDataOne'          => 1,
			'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
			'testMetaAnnotationKey' => $expected,
		];
		$client   = Mockery::mock( MixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $client, 'testMetaAnnotationKey' );
		$this->assertEquals( $expected, $metaAnnotationKeyAccessor->get() );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testInputIsNotArrayAndThrowsException() {
		$response = 'hi';
		$client   = Mockery::mock( MixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $client, 'testMetaAnnotationKey' );
		$this->expectException( UnexpectedValueException::class );
		$metaAnnotationKeyAccessor->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyDoesNotExistAndThrowsException() {
		$response = [
			'otherDataOne' => 1,
			'otherDataTwo' => [ 'asdf', 'fda', [ 'asdf' ] ],
		];
		$client   = Mockery::mock( MixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $client, 'testMetaAnnotationKey' );
		$this->expectException( UnexpectedValueException::class );
		$metaAnnotationKeyAccessor->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsButDataTypeIsInvalidScalarAndThrowsException() {
		$response = [
			'otherDataOne'          => 1,
			'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
			'testMetaAnnotationKey' => 'hi',
		];
		$client   = Mockery::mock( MixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $client, 'testMetaAnnotationKey' );
		$this->expectException( UnexpectedValueException::class );
		$metaAnnotationKeyAccessor->get();
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testKeyExistsButDataTypeIsInvalidIndexedArrayAndThrowsException() {
		$response = [
			'otherDataOne'          => 1,
			'otherDataTwo'          => [ 'asdf', 'fda', [ 'asdf' ] ],
			'testMetaAnnotationKey' => [ 'hello', 'world' ],
		];
		$client   = Mockery::mock( MixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $client, 'testMetaAnnotationKey' );
		$this->expectException( UnexpectedValueException::class );
		$metaAnnotationKeyAccessor->get();
	}
}
