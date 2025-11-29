<?php
/**
 * Local Theme Package Meta Provider Test
 *
 * Tests for the sut that reads and extracts metadata from local theme files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Value\PackageMeta\Theme
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageMetaProviderORASHub\Value\PackageMeta\Theme\StandardThemePackageMetaValue;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Mockery\MockInterface;

/**
 * Tests for the theme package metadata sut implementation.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderTest extends TestCase {

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
		$this->logger = Mockery::mock( LoggerInterface::class );
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
	protected function getLogger(): LoggerInterface {
		self::assertNotNull( $this->logger );
		return $this->logger;
	}

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
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-sut-local';
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
		$sut                              = new StandardThemePackageMetaValue( $response, $this->getLogger() );
		$this->assertEquals( $nameExpected, $sut->getName() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $versionExpected, $sut->getVersion() );
		$this->assertEquals( $viewURLExpected, $sut->getViewURL() );
		$this->assertEquals( $downloadURLExpected, $sut->getDownloadURL() );
		$this->assertEquals( $testedExpected, $sut->getTested() );
		$this->assertEquals( $stableExpected, $sut->getStable() );
		$this->assertEquals( $tagsExpected, $sut->getTags() );
		$this->assertEquals( $authorExpected, $sut->getAuthor() );
		$this->assertEquals( $authorURLExpected, $sut->getAuthorURL() );
		$this->assertEquals( $licenseExpected, $sut->getLicense() );
		$this->assertEquals( $licenseURLExpected, $sut->getLicenseURL() );
		$this->assertEquals( $shortDescriptionExpected, $sut->getShortDescription() );
		$this->assertEquals( $descriptionExpected, $sut->getDescription() );
		$this->assertEquals( $requiresWordPressVersionExpected, $sut->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $sut->getRequiresPHPVersion() );
		$this->assertEquals( $templateExpected, $sut->getTemplate() );
		$this->assertEquals( $statusExpected, $sut->getStatus() );
		$this->assertEquals( $textDomainExpected, $sut->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $sut->getDomainPath() );
		$this->assertEquals( $iconsExpected, $sut->getIcons() );
		$this->assertEquals( $bannersExpected, $sut->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $sut->getBannersRtl() );
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
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-sut-local';
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
		$sut                              = new StandardThemePackageMetaValue( $response, $this->getLogger() );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		$encoded = json_encode( $sut );
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
		$sut                              = new StandardThemePackageMetaValue( $response, $this->getLogger() );
		$this->assertEquals( $nameExpected, $sut->getName() );
		$this->assertEquals( $fullSlugExpected, $sut->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $sut->getShortSlug() );
		$this->assertEquals( $versionExpected, $sut->getVersion() );
		$this->assertEquals( $viewURLExpected, $sut->getViewURL() );
		$this->assertEquals( $downloadURLExpected, $sut->getDownloadURL() );
		$this->assertEquals( $testedExpected, $sut->getTested() );
		$this->assertEquals( $stableExpected, $sut->getStable() );
		$this->assertEquals( $tagsExpected, $sut->getTags() );
		$this->assertEquals( $authorExpected, $sut->getAuthor() );
		$this->assertEquals( $authorURLExpected, $sut->getAuthorURL() );
		$this->assertEquals( $licenseExpected, $sut->getLicense() );
		$this->assertEquals( $licenseURLExpected, $sut->getLicenseURL() );
		$this->assertEquals( $shortDescriptionExpected, $sut->getShortDescription() );
		$this->assertEquals( $descriptionExpected, $sut->getDescription() );
		$this->assertEquals( $requiresWordPressVersionExpected, $sut->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $sut->getRequiresPHPVersion() );
		$this->assertEquals( $templateExpected, $sut->getTemplate() );
		$this->assertEquals( $statusExpected, $sut->getStatus() );
		$this->assertEquals( $textDomainExpected, $sut->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $sut->getDomainPath() );
		$this->assertEquals( $iconsExpected, $sut->getIcons() );
		$this->assertEquals( $bannersExpected, $sut->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $sut->getBannersRtl() );
	}
}
