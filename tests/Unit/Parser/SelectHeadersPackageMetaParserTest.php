<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Parser;

use CodeKaizen\WPPackageMetaProviderLocal\Parser\PackageMeta\SelectHeadersPackageMetaParser;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

class SelectHeadersPackageMetaParserTest extends TestCase
{
    public function testPluginHeadersWithStandardHeadersForPluginFileMyBasicsPlugin(): void
    {
        $headers =             [
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
            // '_sitewide'       => 'Site Wide Only',
        ];
        $filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
        $reader = new FileContentReader();
        $parser = new SelectHeadersPackageMetaParser($headers);
        $parsed = $parser->parse($reader->read($filePath));
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('My Basics Plugin', $parsed['Name']);
        $this->assertArrayHasKey('PluginURI', $parsed);
        $this->assertEquals('https://example.com/plugins/the-basics/', $parsed['PluginURI']);
    }
    public function testPluginHeadersWithoutNameForPluginFileMyBasicsPlugin(): void
    {
        $headers =             [
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
            // '_sitewide'       => 'Site Wide Only',
        ];
        $filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
        $reader = new FileContentReader();
        $parser = new SelectHeadersPackageMetaParser($headers);
        $parsed = $parser->parse($reader->read($filePath));
        $this->assertArrayNotHasKey('Name', $parsed);
    }
    public function testPluginHeadersWithMissingOptionalHeaderForPluginFileMyBasicsPlugin(): void
    {
        $headers =             [
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
            // '_sitewide'       => 'Site Wide Only',
        ];
        $filePath = FixturePathHelper::getPathForPlugin() . '/minimum-headers-plugin.php';
        $reader = new FileContentReader();
        $parser = new SelectHeadersPackageMetaParser($headers);
        $parsed = $parser->parse($reader->read($filePath));
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('Minimum Headers Plugin', $parsed['Name']);
        $this->assertArrayNotHasKey('PluginURI', $parsed);
    }
    public function testPluginHeadersWithStandardHeadersForPluginFilePluginName(): void
    {
        $headers =             [
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
            // '_sitewide'       => 'Site Wide Only',
        ];
        $filePath = FixturePathHelper::getPathForPlugin() . '/plugin-name.php';
        $reader = new FileContentReader();
        $parser = new SelectHeadersPackageMetaParser($headers);
        $parsed = $parser->parse($reader->read($filePath));
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('Plugin Name', $parsed['Name']);
    }
    public function testThemeHeadersWithStandardHeadersForThemeStyleCSSFabledSunset(): void
    {
        $headers = [
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
        $reader = new FileContentReader();
        $parser = new SelectHeadersPackageMetaParser($headers);
        $parsed = $parser->parse($reader->read($filePath));
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('Fabled Sunset', $parsed['Name']);
        $this->assertArrayHasKey('ThemeURI', $parsed);
        $this->assertEquals('https://example.com/fabled-sunset', $parsed['ThemeURI']);
    }
}
