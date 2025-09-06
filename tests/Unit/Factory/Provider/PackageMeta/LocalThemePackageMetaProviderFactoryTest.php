<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\LocalThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageMetaProviderLocalTests\Helper\FixturePathHelper;
use PHPUnit\Framework\TestCase;

class LocalThemePackageMetaProviderFactoryTest extends TestCase
{
    public function testThemePackageMetaFabledSunsetThemeGetName()
    {
        $filePath = FixturePathHelper::getPathForTheme() . '/fabled-sunset/style.css';
        $factory = new LocalThemePackageMetaProviderFactory($filePath);
        $provider = $factory->create();
        $this->assertEquals('Fabled Sunset', $provider->getName());
    }
}
