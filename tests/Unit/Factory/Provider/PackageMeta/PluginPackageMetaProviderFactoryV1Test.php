<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Undocumented class
 */
class PluginPackageMetaProviderFactoryV1Test extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testFactoryCreatesProvider(): void {
		Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider',
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract'
		);
		$url               = 'http://example.com';
		$metaAnnotationKey = 'org.example.meta';
		$logger            = Mockery::mock( LoggerInterface::class );
		$sut               = new PluginPackageMetaProviderFactoryV1(
			$url,
			$metaAnnotationKey,
			$logger
		);
		$return            = $sut->create();
		$this->assertInstanceOf(
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract',
			$return
		);
	}
}
