<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Parser;

use CodeKaizen\WPPackageMetaProviderLocal\Parser\PackageMeta\SelectHeadersPackageMetaParser;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SelectHeadersPackageMetaParserTest extends TestCase
{
    public function testPluginHeadersWithStandardHeadersForPluginFileMyBasicsPlugin()
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
        $this->assertIsArray($parsed);
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('My Basics Plugin', $parsed['Name']);
        $this->assertArrayHasKey('PluginURI', $parsed);
        $this->assertEquals('https://example.com/plugins/the-basics/', $parsed['PluginURI']);
    }
    public function testPluginHeadersWithoutNameForPluginFileMyBasicsPlugin()
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
        $this->assertIsArray($parsed);
        $this->assertArrayNotHasKey('Name', $parsed);
        $this->assertArrayHasKey('PluginURI', $parsed);
        $this->assertEquals('https://example.com/plugins/the-basics/', $parsed['PluginURI']);
    }
    public function testPluginHeadersWithStandardHeadersForPluginFilePluginName()
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
        $this->assertIsArray($parsed);
        $this->assertArrayHasKey('Name', $parsed);
        $this->assertEquals('Plugin Name', $parsed['Name']);
    }
}
