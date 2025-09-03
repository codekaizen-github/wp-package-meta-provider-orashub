<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Provider\PackageMeta\LocalThemePackageMetaProvider;
use CodeKaizen\WPPackageMetaProviderLocal\Reader\FileContentReader;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

class LocalThemePackageMetaProviderTest extends TestCase
{
    public function testGetNameFromThemeFabledSunset(): void
    {
        $filePath = FixturePathHelper::getPathForTheme() . '/fabled-sunset/style.css';
        $reader = new FileContentReader();
        $provider = new LocalThemePackageMetaProvider($filePath, $reader);
        $this->assertEquals('Fabled Sunset', $provider->getName());
    }
}
