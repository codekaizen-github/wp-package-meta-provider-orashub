<?php
/**
 * Select Headers Package Meta Parser Test
 *
 * Tests for parsing and extracting package metadata headers from plugin and theme files.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Parser
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Parser;

use CodeKaizen\WPPackageMetaProviderORASHub\Parser\PackageMeta\SelectHeadersPackageMetaParser;
use CodeKaizen\WPPackageMetaProviderORASHub\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderORASHubTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the parser that extracts specific headers from package files.
 *
 * @since 1.0.0
 */
class SelectHeadersPackageMetaParserTest extends TestCase {

	/**
	 * Tests parsing of standard plugin headers from My Basics plugin file.
	 *
	 * @return void
	 */
	public function testPluginHeadersWithStandardHeadersForPluginFileMyBasicsPlugin(): void {
		$headers  = [
			'Name'            => 'Plugin Name',
			'PluginURI'       => 'Plugin URI',
			'Version'         => 'Version',
			'Description'     => 'Description',
			'Author'          => 'Author',
			'AuthorURI'       => 'Author URI',
			'TextDomain'      => 'Text Domain',
			'DomainPath'      => 'Domain Path',
			'Network'         => 'Network',
			'RequiresWP'      => 'Requires at least',
			'RequiresPHP'     => 'Requires PHP',
			'UpdateURI'       => 'Update URI',
			'RequiresPlugins' => 'Requires Plugins',
		];
		$filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
		$reader   = new FileContentReader();
		$parser   = new SelectHeadersPackageMetaParser( $headers );
		$parsed   = $parser->parse( $reader->read( $filePath ) );
		$this->assertArrayHasKey( 'Name', $parsed );
		$this->assertEquals( 'My Basics Plugin', $parsed['Name'] );
		$this->assertArrayHasKey( 'PluginURI', $parsed );
		$this->assertEquals( 'https://example.com/plugins/the-basics/', $parsed['PluginURI'] );
	}
	/**
	 * Tests parsing of plugin headers without Name field from My Basics plugin file.
	 *
	 * @return void
	 */
	public function testPluginHeadersWithoutNameForPluginFileMyBasicsPlugin(): void {
		$headers  = [
			'PluginURI'       => 'Plugin URI',
			'Version'         => 'Version',
			'Description'     => 'Description',
			'Author'          => 'Author',
			'AuthorURI'       => 'Author URI',
			'TextDomain'      => 'Text Domain',
			'DomainPath'      => 'Domain Path',
			'Network'         => 'Network',
			'RequiresWP'      => 'Requires at least',
			'RequiresPHP'     => 'Requires PHP',
			'UpdateURI'       => 'Update URI',
			'RequiresPlugins' => 'Requires Plugins',
		];
		$filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
		$reader   = new FileContentReader();
		$parser   = new SelectHeadersPackageMetaParser( $headers );
		$parsed   = $parser->parse( $reader->read( $filePath ) );
		$this->assertArrayNotHasKey( 'Name', $parsed );
	}
	/**
	 * Tests parsing of plugin headers with missing optional headers from My Basics plugin file.
	 *
	 * @return void
	 */
	public function testPluginHeadersWithMissingOptionalHeaderForPluginFileMyBasicsPlugin(): void {
		$headers = [
			'Name'            => 'Plugin Name',
			'PluginURI'       => 'Plugin URI',
			'Version'         => 'Version',
			'Description'     => 'Description',
			'Author'          => 'Author',
			'AuthorURI'       => 'Author URI',
			'TextDomain'      => 'Text Domain',
			'DomainPath'      => 'Domain Path',
			'Network'         => 'Network',
			'RequiresWP'      => 'Requires at least',
			'RequiresPHP'     => 'Requires PHP',
			'UpdateURI'       => 'Update URI',
			'RequiresPlugins' => 'Requires Plugins',
			// Site Wide Only is deprecated in favor of Network.
			// Legacy header that has been replaced by Network.
		];
		$filePath = FixturePathHelper::getPathForPlugin() . '/minimum-headers-plugin.php';
		$reader   = new FileContentReader();
		$parser   = new SelectHeadersPackageMetaParser( $headers );
		$parsed   = $parser->parse( $reader->read( $filePath ) );
		$this->assertArrayHasKey( 'Name', $parsed );
		$this->assertEquals( 'Minimum Headers Plugin', $parsed['Name'] );
		$this->assertArrayNotHasKey( 'PluginURI', $parsed );
	}
	/**
	 * Tests parsing of standard plugin headers from a basic plugin file.
	 *
	 * @return void
	 */
	public function testPluginHeadersWithStandardHeadersForPluginFilePluginName(): void {
		$headers  = [
			'Name'            => 'Plugin Name',
			'PluginURI'       => 'Plugin URI',
			'Version'         => 'Version',
			'Description'     => 'Description',
			'Author'          => 'Author',
			'AuthorURI'       => 'Author URI',
			'TextDomain'      => 'Text Domain',
			'DomainPath'      => 'Domain Path',
			'Network'         => 'Network',
			'RequiresWP'      => 'Requires at least',
			'RequiresPHP'     => 'Requires PHP',
			'UpdateURI'       => 'Update URI',
			'RequiresPlugins' => 'Requires Plugins',
		];
		$filePath = FixturePathHelper::getPathForPlugin() . '/plugin-name.php';
		$reader   = new FileContentReader();
		$parser   = new SelectHeadersPackageMetaParser( $headers );
		$parsed   = $parser->parse( $reader->read( $filePath ) );
		$this->assertArrayHasKey( 'Name', $parsed );
		$this->assertEquals( 'Plugin Name', $parsed['Name'] );
	}
	/**
	 * Tests parsing of standard theme headers from the Fabled Sunset theme's style.css.
	 *
	 * @return void
	 */
	public function testThemeHeadersWithStandardHeadersForThemeStyleCSSFabledSunset(): void {
		$headers  = [
			'Name'        => 'Theme Name',
			'ThemeURI'    => 'Theme URI',
			'Description' => 'Description',
			'Author'      => 'Author',
			'AuthorURI'   => 'Author URI',
			'Version'     => 'Version',
			'Template'    => 'Template',
			'Status'      => 'Status',
			'Tags'        => 'Tags',
			'TextDomain'  => 'Text Domain',
			'DomainPath'  => 'Domain Path',
			'RequiresWP'  => 'Requires at least',
			'RequiresPHP' => 'Requires PHP',
			'UpdateURI'   => 'Update URI',
		];
		$filePath = FixturePathHelper::getPathForTheme() . '/fabled-sunset/style.css';
		$reader   = new FileContentReader();
		$parser   = new SelectHeadersPackageMetaParser( $headers );
		$parsed   = $parser->parse( $reader->read( $filePath ) );
		$this->assertArrayHasKey( 'Name', $parsed );
		$this->assertEquals( 'Fabled Sunset', $parsed['Name'] );
		$this->assertArrayHasKey( 'ThemeURI', $parsed );
		$this->assertEquals( 'https://example.com/fabled-sunset', $parsed['ThemeURI'] );
	}
}
