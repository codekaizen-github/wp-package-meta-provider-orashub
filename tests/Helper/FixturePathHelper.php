<?php
/**
 * Fixture Path Helper
 *
 * Provides utility methods for accessing test fixture file paths.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocalTests\Helper
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Helper;

/**
 * Helper class for working with fixture file paths.
 *
 * @since 1.0.0
 */
class FixturePathHelper {

	/**
	 * Gets the base path to the test fixtures directory.
	 *
	 * @return string Absolute path to the test fixtures directory.
	 */
	public static function getBasePath(): string {
		return __DIR__ . '/../Fixture';
	}
	/**
	 * Gets the path to the general file fixtures directory.
	 *
	 * @return string Absolute path to the file fixtures directory.
	 */
	public static function getPathForFile(): string {
		return static::getBasePath() . '/File';
	}
	/**
	 * Gets the path to the plugin fixtures directory.
	 *
	 * @return string Absolute path to the plugin fixtures directory.
	 */
	public static function getPathForPlugin(): string {
		return static::getBasePath() . '/Plugin';
	}
	/**
	 * Gets the path to the theme fixtures directory.
	 *
	 * @return string Absolute path to the theme fixtures directory.
	 */
	public static function getPathForTheme(): string {
		return static::getBasePath() . '/Theme';
	}
}
