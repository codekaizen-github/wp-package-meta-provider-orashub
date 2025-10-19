<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Undocumented class
 */
class ThemePackageMetaProviderFactoryV1Test extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testFactoryCreatesProvider(): void {
		Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\ThemePackageMetaProvider',
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract'
		);
		$url               = 'http://example.com';
		$metaAnnotationKey = 'org.example.meta';
		$logger            = Mockery::mock( LoggerInterface::class );
		$sut               = new ThemePackageMetaProviderFactoryV1(
			$url,
			$metaAnnotationKey,
			$logger
		);
		$return            = $sut->create();
		$this->assertInstanceOf(
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract',
			$return
		);
	}
}
