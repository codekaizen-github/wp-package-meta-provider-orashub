<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalPluginPackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

class LocalPluginPackageMetaProviderTest extends TestCase
{
    public function testGetNameFromPluginMyBasicsPlugin(): void
    {
        $filePath = FixturePathHelper::getPathForPlugin() . '/my-basics-plugin.php';
        $reader = new FileContentReader();
        $provider = new LocalPluginPackageMetaProvider($filePath, $reader);
        $this->assertEquals('My Basics Plugin', $provider->getName());
    }
}
