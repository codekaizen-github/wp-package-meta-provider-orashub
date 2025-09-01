<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Helper;

class FixturePathHelper
{
    public static function getBasePath()
    {
        return __DIR__ . '/../Fixture';
    }
    public static function getPathForFile()
    {
        return static::getBasePath() . '/File';
    }
    public static function getPathForPlugin()
    {
        return static::getBasePath() . '/Plugin';
    }
    public static function getPathForTheme()
    {
        return static::getBasePath() . '/Theme';
    }
}
