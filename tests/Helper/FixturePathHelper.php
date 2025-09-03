<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Helper;

class FixturePathHelper
{
    public static function getBasePath(): string
    {
        return __DIR__ . '/../Fixture';
    }
    public static function getPathForFile(): string
    {
        return static::getBasePath() . '/File';
    }
    public static function getPathForPlugin(): string
    {
        return static::getBasePath() . '/Plugin';
    }
    public static function getPathForTheme(): string
    {
        return static::getBasePath() . '/Theme';
    }
}
