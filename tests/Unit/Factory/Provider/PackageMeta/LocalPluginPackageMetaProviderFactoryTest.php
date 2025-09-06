<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\LocalPluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

class LocalPluginPackageMetaProviderFactoryTest extends TestCase
{
    public function testPluginPackageMetaMyBasicsPluginGetName(): void
    {
        $filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
        $factory = new LocalPluginPackageMetaProviderFactory($filePath);
        $provider = $factory->create();
        $this->assertEquals('My Basics Plugin', $provider->getName());
    }
    public function testPluginPackageMetaMinimumHeadersPluginGetName(): void
    {
        $filePath = FixturePathHelper::getPathForPlugin() . '/minimum-headers-plugin.php';
        $factory = new LocalPluginPackageMetaProviderFactory($filePath);
        $provider = $factory->create();
        $this->assertEquals('Minimum Headers Plugin', $provider->getName());
    }
}
