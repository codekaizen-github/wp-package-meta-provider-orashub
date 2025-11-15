<?php
/**
 * Test
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\Value\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\Value\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\PluginPackageMetaValueServiceV1;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Undocumented class
 */
class PluginPackageMetaValueServiceV1Test extends TestCase {
	/**
	 * Undocumented function
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testFactoryCreatesProvider(): void {
		Mockery::mock(
			'overload:CodeKaizen\WPPackageMetaProviderORASHub\Service\PackageMeta\PluginPackageMetaValue',
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Service\PackageMeta\PluginPackageMetaValueContract'
		);
		$url               = 'http://example.com';
		$metaAnnotationKey = 'org.example.meta';
		$httpOptions       = [];
		$logger            = Mockery::mock( LoggerInterface::class );
		$sut               = new PluginPackageMetaValueServiceV1(
			$url,
			$metaAnnotationKey,
			$httpOptions,
			$logger
		);
		$return            = $sut->create();
		$this->assertInstanceOf(
			'CodeKaizen\WPPackageMetaProviderContract\Contract\Service\PackageMeta\PluginPackageMetaValueContract',
			$return
		);
	}
}
