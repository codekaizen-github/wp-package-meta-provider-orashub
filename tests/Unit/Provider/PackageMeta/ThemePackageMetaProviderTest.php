<?php
/**
 * Local Theme Package Meta Provider Test
 *
 * Tests for the provider that reads and extracts metadata from local theme files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Service\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Service\PackageMeta\ThemePackageMetaProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests for the theme package metadata provider implementation.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct plugin name from the My Basics plugin.
	 *
	 * @return void
	 */
	public function testGetNameFromThemeFabledSunset(): void {
		$nameExpected                     = 'Test Theme';
		$fullSlugExpected                 = 'test-theme/style.css';
		$shortSlugExpected                = 'test-theme';
		$versionExpected                  = '3.0.1';
		$viewURLExpected                  = 'https://codekaizen.net';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$tagsExpected                     = [
			'awesome',
			'cool',
			'test',
		];
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$shortDescriptionExpected         = 'This is a test theme';
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$templateExpected                 = 'parent-theme';
		$statusExpected                   = 'publish';
		$textDomainExpected               = 'test-theme';
		$domainPathExpected               = '/languages';
		$iconsExpected                    = [
			'1x'  => 'https://example.com/icon-128x128.png',
			'2x'  => 'https://example.com/icon-256x256.png',
			'svg' => 'https://example.com/icon.svg',
		];
		$bannersExpected                  = [
			'1x' => 'https://example.com/banner-772x250.png',
			'2x' => 'https://example.com/banner-1544x500.png',
		];
		$bannersRtlExpected               = [
			'1x' => 'https://example.com/banner-rtl-772x250.png',
			'2x' => 'https://example.com/banner-rtl-1544x500.png',
		];
		$testedExpected                   = '6.8.2';
		$stableExpected                   = '6.8.2';
		$licenseExpected                  = 'GPL v2 or later';
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$descriptionExpected              = 'A longer description.';
		$response                         = [
			'name'                     => $nameExpected,
			'fullSlug'                 => $fullSlugExpected,
			'shortSlug'                => $shortSlugExpected,
			'version'                  => $versionExpected,
			'viewUrl'                  => $viewURLExpected,
			'downloadUrl'              => $downloadURLExpected,
			'tested'                   => $testedExpected,
			'stable'                   => $stableExpected,
			'tags'                     => $tagsExpected,
			'author'                   => $authorExpected,
			'authorUrl'                => $authorURLExpected,
			'license'                  => $licenseExpected,
			'licenseUrl'               => $licenseURLExpected,
			'description'              => $descriptionExpected,
			'shortDescription'         => $shortDescriptionExpected,
			'requiresWordPressVersion' => $requiresWordPressVersionExpected,
			'requiresPHPVersion'       => $requiresPHPVersionExpected,
			'textDomain'               => $textDomainExpected,
			'domainPath'               => $domainPathExpected,
			'icons'                    => $iconsExpected,
			'banners'                  => $bannersExpected,
			'bannersRtl'               => $bannersRtlExpected,
			'template'                 => $templateExpected,
			'status'                   => $statusExpected,
		];
		$metaAnnotationKeyAccessor        = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new ThemePackageMetaProvider( $metaAnnotationKeyAccessor, $logger );
		$this->assertEquals( $nameExpected, $provider->getName() );
		$this->assertEquals( $fullSlugExpected, $provider->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $provider->getShortSlug() );
		$this->assertEquals( $versionExpected, $provider->getVersion() );
		$this->assertEquals( $viewURLExpected, $provider->getViewURL() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $authorExpected, $provider->getAuthor() );
		$this->assertEquals( $authorURLExpected, $provider->getAuthorURL() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $shortDescriptionExpected, $provider->getShortDescription() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $templateExpected, $provider->getTemplate() );
		$this->assertEquals( $statusExpected, $provider->getStatus() );
		$this->assertEquals( $textDomainExpected, $provider->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $provider->getDomainPath() );
		$this->assertEquals( $iconsExpected, $provider->getIcons() );
		$this->assertEquals( $bannersExpected, $provider->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $provider->getBannersRtl() );
	}
	/**
	 * Test
	 *
	 * @return void
	 */
	public function testJSONEncodeAndDecode(): void {
		$nameExpected                     = 'Test Theme';
		$fullSlugExpected                 = 'test-theme/style.css';
		$shortSlugExpected                = 'test-theme';
		$versionExpected                  = '3.0.1';
		$viewURLExpected                  = 'https://codekaizen.net';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$tagsExpected                     = [
			'awesome',
			'cool',
			'test',
		];
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$shortDescriptionExpected         = 'This is a test theme';
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$templateExpected                 = 'parent-theme';
		$statusExpected                   = 'publish';
		$textDomainExpected               = 'test-theme';
		$domainPathExpected               = '/languages';
		$iconsExpected                    = [
			'1x'  => 'https://example.com/icon-128x128.png',
			'2x'  => 'https://example.com/icon-256x256.png',
			'svg' => 'https://example.com/icon.svg',
		];
		$bannersExpected                  = [
			'1x' => 'https://example.com/banner-772x250.png',
			'2x' => 'https://example.com/banner-1544x500.png',
		];
		$bannersRtlExpected               = [
			'1x' => 'https://example.com/banner-rtl-772x250.png',
			'2x' => 'https://example.com/banner-rtl-1544x500.png',
		];
		$testedExpected                   = '6.8.2';
		$stableExpected                   = '6.8.2';
		$licenseExpected                  = 'GPL v2 or later';
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$descriptionExpected              = 'A longer description.';
		$response                         = [
			'name'                     => $nameExpected,
			'fullSlug'                 => $fullSlugExpected,
			'shortSlug'                => $shortSlugExpected,
			'version'                  => $versionExpected,
			'viewUrl'                  => $viewURLExpected,
			'downloadUrl'              => $downloadURLExpected,
			'tested'                   => $testedExpected,
			'stable'                   => $stableExpected,
			'tags'                     => $tagsExpected,
			'author'                   => $authorExpected,
			'authorUrl'                => $authorURLExpected,
			'license'                  => $licenseExpected,
			'licenseUrl'               => $licenseURLExpected,
			'description'              => $descriptionExpected,
			'shortDescription'         => $shortDescriptionExpected,
			'requiresWordPressVersion' => $requiresWordPressVersionExpected,
			'requiresPHPVersion'       => $requiresPHPVersionExpected,
			'textDomain'               => $textDomainExpected,
			'domainPath'               => $domainPathExpected,
			'icons'                    => $iconsExpected,
			'banners'                  => $bannersExpected,
			'bannersRtl'               => $bannersRtlExpected,
			'template'                 => $templateExpected,
			'status'                   => $statusExpected,
		];
		$metaAnnotationKeyAccessor        = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new ThemePackageMetaProvider( $metaAnnotationKeyAccessor, $logger );
		$this->assertEquals( 'Test Theme', $provider->getName() );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$encoded = json_encode( $provider );
		$this->assertIsString( $encoded );
		$decoded = json_decode( $encoded, true );
		$this->assertIsArray( $decoded );
		$this->assertArrayHasKey( 'name', $decoded );
		$this->assertEquals( $nameExpected, $decoded['name'] );
		$this->assertArrayHasKey( 'fullSlug', $decoded );
		$this->assertEquals( $fullSlugExpected, $decoded['fullSlug'] );
		$this->assertArrayHasKey( 'shortSlug', $decoded );
		$this->assertEquals( $shortSlugExpected, $decoded['shortSlug'] );
		$this->assertArrayHasKey( 'version', $decoded );
		$this->assertEquals( $versionExpected, $decoded['version'] );
		$this->assertArrayHasKey( 'viewUrl', $decoded );
		$this->assertEquals( $viewURLExpected, $decoded['viewUrl'] );
		$this->assertArrayHasKey( 'downloadUrl', $decoded );
		$this->assertEquals( $downloadURLExpected, $decoded['downloadUrl'] );
		$this->assertArrayHasKey( 'tested', $decoded );
		$this->assertEquals( $testedExpected, $decoded['tested'] );
		$this->assertArrayHasKey( 'stable', $decoded );
		$this->assertEquals( $stableExpected, $decoded['stable'] );
		$this->assertArrayHasKey( 'tags', $decoded );
		$this->assertEquals( $tagsExpected, $decoded['tags'] );
		$this->assertArrayHasKey( 'author', $decoded );
		$this->assertEquals( $authorExpected, $decoded['author'] );
		$this->assertArrayHasKey( 'authorUrl', $decoded );
		$this->assertEquals( $authorURLExpected, $decoded['authorUrl'] );
		$this->assertArrayHasKey( 'license', $decoded );
		$this->assertEquals( $licenseExpected, $decoded['license'] );
		$this->assertArrayHasKey( 'licenseUrl', $decoded );
		$this->assertEquals( $licenseURLExpected, $decoded['licenseUrl'] );
		$this->assertArrayHasKey( 'shortDescription', $decoded );
		$this->assertEquals( $shortDescriptionExpected, $decoded['shortDescription'] );
		$this->assertArrayHasKey( 'description', $decoded );
		$this->assertEquals( $descriptionExpected, $decoded['description'] );
		$this->assertArrayHasKey( 'requiresWordPressVersion', $decoded );
		$this->assertEquals( $requiresWordPressVersionExpected, $decoded['requiresWordPressVersion'] );
		$this->assertArrayHasKey( 'requiresPHPVersion', $decoded );
		$this->assertEquals( $requiresPHPVersionExpected, $decoded['requiresPHPVersion'] );
		$this->assertArrayHasKey( 'template', $decoded );
		$this->assertEquals( $templateExpected, $decoded['template'] );
		$this->assertArrayHasKey( 'status', $decoded );
		$this->assertEquals( $statusExpected, $decoded['status'] );
		$this->assertArrayHasKey( 'textDomain', $decoded );
		$this->assertEquals( $textDomainExpected, $decoded['textDomain'] );
		$this->assertArrayHasKey( 'domainPath', $decoded );
		$this->assertEquals( $domainPathExpected, $decoded['domainPath'] );
		$this->assertArrayHasKey( 'icons', $decoded );
		$this->assertEquals( $iconsExpected, $decoded['icons'] );
		$this->assertArrayHasKey( 'banners', $decoded );
		$this->assertEquals( $bannersExpected, $decoded['banners'] );
		$this->assertArrayHasKey( 'bannersRtl', $decoded );
		$this->assertEquals( $bannersRtlExpected, $decoded['bannersRtl'] );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testBareMinimumFieldsValid(): void {
		$nameExpected                     = 'Test Theme';
		$fullSlugExpected                 = 'test-theme/style.css';
		$shortSlugExpected                = 'test-theme';
		$viewURLExpected                  = null;
		$versionExpected                  = null;
		$downloadURLExpected              = null;
		$testedExpected                   = null;
		$stableExpected                   = null;
		$tagsExpected                     = [];
		$authorExpected                   = null;
		$authorURLExpected                = null;
		$licenseExpected                  = null;
		$licenseURLExpected               = null;
		$descriptionExpected              = null;
		$shortDescriptionExpected         = null;
		$requiresWordPressVersionExpected = null;
		$requiresPHPVersionExpected       = null;
		$templateExpected                 = null;
		$statusExpected                   = null;
		$textDomainExpected               = null;
		$domainPathExpected               = null;
		$iconsExpected                    = [];
		$bannersExpected                  = [];
		$bannersRtlExpected               = [];
		$response                         = [
			'name'      => $nameExpected,
			'fullSlug'  => $fullSlugExpected,
			'shortSlug' => $shortSlugExpected,
		];
		$metaAnnotationKeyAccessor        = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new ThemePackageMetaProvider( $metaAnnotationKeyAccessor, $logger );
		$this->assertEquals( $nameExpected, $provider->getName() );
		$this->assertEquals( $fullSlugExpected, $provider->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $provider->getShortSlug() );
		$this->assertEquals( $versionExpected, $provider->getVersion() );
		$this->assertEquals( $viewURLExpected, $provider->getViewURL() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $authorExpected, $provider->getAuthor() );
		$this->assertEquals( $authorURLExpected, $provider->getAuthorURL() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $shortDescriptionExpected, $provider->getShortDescription() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $templateExpected, $provider->getTemplate() );
		$this->assertEquals( $statusExpected, $provider->getStatus() );
		$this->assertEquals( $textDomainExpected, $provider->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $provider->getDomainPath() );
		$this->assertEquals( $iconsExpected, $provider->getIcons() );
		$this->assertEquals( $bannersExpected, $provider->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $provider->getBannersRtl() );
	}
}
