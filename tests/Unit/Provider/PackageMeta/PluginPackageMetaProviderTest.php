<?php
/**
 * Local Plugin Package Meta Provider Test
 *
 * Tests for the provider that reads and extracts metadata from local plugin files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests for the plugin package metadata provider implementation.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProviderTest extends TestCase {

	/**
	 * Tests getName() extracts the correct plugin name from the My Basics plugin.
	 *
	 * @return void
	 */
	public function testAllPropertiesFromPluginMyBasicsPlugin(): void {
		$nameExpected                     = 'Test Plugin';
		$fullSlugExpected                 = 'test-plugin/test-plugin.php';
		$shortSlugExpected                = 'test-plugin';
		$viewURLExpected                  = 'https://codekaizen.net';
		$versionExpected                  = '3.0.1';
		$shortDescriptionExpected         = 'This is a test plugin';
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$textDomainExpected               = 'test-plugin';
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
		$networkExpected                  = true;
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$testedExpected                   = '6.8.2';
		$stableExpected                   = '6.8.2';
		$requiresPluginsExpected          = [ 'akismet', 'hello-dolly' ];
		$tagsExpected                     = [ 'tag1', 'tag2', 'tag3' ];
		$licenseExpected                  = 'GPL v2 or later';
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$descriptionExpected              = 'This is a long description of the plugin';
		$sectionsExpected                 = [
			'changelog' => 'changed',
			'about'     => 'this is a plugin about section',
		];
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
			'requiresPlugins'          => $requiresPluginsExpected,
			'sections'                 => $sectionsExpected,
			'network'                  => $networkExpected,
		];
		$metaAnnotationKeyAccessor        = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new PluginPackageMetaProvider( $metaAnnotationKeyAccessor, $logger );
		$this->assertEquals( $nameExpected, $provider->getName() );
		$this->assertEquals( $fullSlugExpected, $provider->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $provider->getShortSlug() );
		$this->assertEquals( $viewURLExpected, $provider->getViewURL() );
		$this->assertEquals( $versionExpected, $provider->getVersion() );
		$this->assertEquals( $shortDescriptionExpected, $provider->getShortDescription() );
		$this->assertEquals( $authorExpected, $provider->getAuthor() );
		$this->assertEquals( $authorURLExpected, $provider->getAuthorURL() );
		$this->assertEquals( $textDomainExpected, $provider->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $provider->getDomainPath() );
		$this->assertEquals( $iconsExpected, $provider->getIcons() );
		$this->assertEquals( $bannersExpected, $provider->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $provider->getBannersRtl() );
		$this->assertEquals( $networkExpected, $provider->getNetwork() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $requiresPluginsExpected, $provider->getRequiresPlugins() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $sectionsExpected, $provider->getSections() );
	}
	/**
	 * Test
	 *
	 * @return void
	 */
	public function testJSONEncodeAndDecode(): void {
		$nameExpected                     = 'Test Plugin';
		$fullSlugExpected                 = 'test-plugin/test-plugin.php';
		$shortSlugExpected                = 'test-plugin';
		$viewURLExpected                  = 'https://codekaizen.net';
		$versionExpected                  = '3.0.1';
		$shortDescriptionExpected         = 'This is a test plugin';
		$authorExpected                   = 'Andrew Dawes';
		$authorURLExpected                = 'https://codekaizen.net/team/andrew-dawes';
		$textDomainExpected               = 'test-plugin';
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
		$networkExpected                  = true;
		$requiresWordPressVersionExpected = '6.8.2';
		$requiresPHPVersionExpected       = '8.2.1';
		$downloadURLExpected              = 'https://github.com/codekaizen-github/wp-package-meta-provider-local';
		$testedExpected                   = '6.8.2';
		$stableExpected                   = '6.8.2';
		$requiresPluginsExpected          = [ 'akismet', 'hello-dolly' ];
		$tagsExpected                     = [ 'tag1', 'tag2', 'tag3' ];
		$licenseExpected                  = 'GPL v2 or later';
		$licenseURLExpected               = 'https://www.gnu.org/licenses/gpl-2.0.html';
		$descriptionExpected              = 'This is a long description of the plugin';
		$sectionsExpected                 = [
			'changelog' => 'changed',
			'about'     => 'this is a plugin about section',
		];
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
			'requiresPlugins'          => $requiresPluginsExpected,
			'sections'                 => $sectionsExpected,
			'network'                  => $networkExpected,
		];
		$client                           = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$client->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new PluginPackageMetaProvider( $client, $logger );
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
		$this->assertArrayHasKey( 'viewUrl', $decoded );
		$this->assertEquals( $viewURLExpected, $decoded['viewUrl'] );
		$this->assertArrayHasKey( 'version', $decoded );
		$this->assertEquals( $versionExpected, $decoded['version'] );
		$this->assertArrayHasKey( 'shortDescription', $decoded );
		$this->assertEquals( $shortDescriptionExpected, $decoded['shortDescription'] );
		$this->assertArrayHasKey( 'author', $decoded );
		$this->assertEquals( $authorExpected, $decoded['author'] );
		$this->assertArrayHasKey( 'authorUrl', $decoded );
		$this->assertEquals( $authorURLExpected, $decoded['authorUrl'] );
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
		$this->assertArrayHasKey( 'network', $decoded );
		$this->assertEquals( $networkExpected, $decoded['network'] );
		$this->assertArrayHasKey( 'requiresWordPressVersion', $decoded );
		$this->assertEquals( $requiresWordPressVersionExpected, $decoded['requiresWordPressVersion'] );
		$this->assertArrayHasKey( 'requiresPHPVersion', $decoded );
		$this->assertEquals( $requiresPHPVersionExpected, $decoded['requiresPHPVersion'] );
		$this->assertArrayHasKey( 'downloadUrl', $decoded );
		$this->assertEquals( $downloadURLExpected, $decoded['downloadUrl'] );
		$this->assertArrayHasKey( 'requiresPlugins', $decoded );
		$this->assertEquals( $requiresPluginsExpected, $decoded['requiresPlugins'] );
		$this->assertArrayHasKey( 'tested', $decoded );
		$this->assertEquals( $testedExpected, $decoded['tested'] );
		$this->assertArrayHasKey( 'stable', $decoded );
		$this->assertEquals( $stableExpected, $decoded['stable'] );
		$this->assertArrayHasKey( 'license', $decoded );
		$this->assertEquals( $licenseExpected, $decoded['license'] );
		$this->assertArrayHasKey( 'licenseUrl', $decoded );
		$this->assertEquals( $licenseURLExpected, $decoded['licenseUrl'] );
		$this->assertArrayHasKey( 'description', $decoded );
		$this->assertEquals( $descriptionExpected, $decoded['description'] );
		$this->assertArrayHasKey( 'tags', $decoded );
		$this->assertEquals( $tagsExpected, $decoded['tags'] );
		$this->assertArrayHasKey( 'sections', $decoded );
		$this->assertEquals( $sectionsExpected, $decoded['sections'] );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testBareMinimumFieldsValid(): void {
		$nameExpected                     = 'Test Plugin';
		$fullSlugExpected                 = 'test-plugin/test-plugin.php';
		$shortSlugExpected                = 'test-plugin';
		$viewURLExpected                  = null;
		$versionExpected                  = null;
		$shortDescriptionExpected         = null;
		$authorExpected                   = null;
		$authorURLExpected                = null;
		$textDomainExpected               = null;
		$domainPathExpected               = null;
		$iconsExpected                    = [];
		$bannersExpected                  = [];
		$bannersRtlExpected               = [];
		$networkExpected                  = false;
		$requiresWordPressVersionExpected = null;
		$requiresPHPVersionExpected       = null;
		$downloadURLExpected              = null;
		$testedExpected                   = null;
		$stableExpected                   = null;
		$requiresPluginsExpected          = [];
		$tagsExpected                     = [];
		$licenseExpected                  = null;
		$licenseURLExpected               = null;
		$descriptionExpected              = null;
		$sectionsExpected                 = [];
		$response                         = [
			'name'      => $nameExpected,
			'fullSlug'  => $fullSlugExpected,
			'shortSlug' => $shortSlugExpected,
		];
		$metaAnnotationKeyAccessor        = Mockery::mock( AssociativeArrayStringToMixedAccessorContract::class );
		$metaAnnotationKeyAccessor->shouldReceive( 'get' )->with()->andReturn( $response );
		$logger   = Mockery::mock( LoggerInterface::class );
		$provider = new PluginPackageMetaProvider( $metaAnnotationKeyAccessor, $logger );
		$this->assertEquals( $nameExpected, $provider->getName() );
		$this->assertEquals( $fullSlugExpected, $provider->getFullSlug() );
		$this->assertEquals( $shortSlugExpected, $provider->getShortSlug() );
		$this->assertEquals( $viewURLExpected, $provider->getViewURL() );
		$this->assertEquals( $versionExpected, $provider->getVersion() );
		$this->assertEquals( $shortDescriptionExpected, $provider->getShortDescription() );
		$this->assertEquals( $authorExpected, $provider->getAuthor() );
		$this->assertEquals( $authorURLExpected, $provider->getAuthorURL() );
		$this->assertEquals( $textDomainExpected, $provider->getTextDomain() );
		$this->assertEquals( $domainPathExpected, $provider->getDomainPath() );
		$this->assertEquals( $iconsExpected, $provider->getIcons() );
		$this->assertEquals( $bannersExpected, $provider->getBanners() );
		$this->assertEquals( $bannersRtlExpected, $provider->getBannersRtl() );
		$this->assertEquals( $networkExpected, $provider->getNetwork() );
		$this->assertEquals( $requiresWordPressVersionExpected, $provider->getRequiresWordPressVersion() );
		$this->assertEquals( $requiresPHPVersionExpected, $provider->getRequiresPHPVersion() );
		$this->assertEquals( $downloadURLExpected, $provider->getDownloadURL() );
		$this->assertEquals( $requiresPluginsExpected, $provider->getRequiresPlugins() );
		$this->assertEquals( $testedExpected, $provider->getTested() );
		$this->assertEquals( $stableExpected, $provider->getStable() );
		$this->assertEquals( $licenseExpected, $provider->getLicense() );
		$this->assertEquals( $licenseURLExpected, $provider->getLicenseURL() );
		$this->assertEquals( $descriptionExpected, $provider->getDescription() );
		$this->assertEquals( $tagsExpected, $provider->getTags() );
		$this->assertEquals( $sectionsExpected, $provider->getSections() );
	}
}
