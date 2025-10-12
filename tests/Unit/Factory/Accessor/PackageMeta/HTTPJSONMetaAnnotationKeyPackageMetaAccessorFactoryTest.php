<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Accessor\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\PackageMeta\HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Undocumented class
 */
class HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactoryTest extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testFactoryCreatesAccessor() {
		$uri               = 'http://example.com';
		$metaAnnotationKey = 'org.example.meta';
		$options           = [];
		$logger            = Mockery::mock( LoggerInterface::class );
		$factory           = new HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory(
			$uri,
			$metaAnnotationKey,
			$options,
			$logger
		);
		$accessor          = $factory->create();

		$this->assertInstanceOf( AssociativeArrayStringToMixedAccessorContract::class, $accessor );
	}
}
