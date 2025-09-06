<?php
/**
 * Flexible Semantic Version Validation Rule
 *
 * This file contains a flexible semantic version validation rule that
 * accepts major-only, major.minor, and full semantic versions.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version;

use Respect\Validation\Rules\Core\Simple;

/**
 * Validation rule for flexible semantic version formats.
 *
 * Allows major version only (5), major.minor (5.2), or full semver (5.2.1).
 * Used for WordPress and PHP version requirements which are often not full semver.
 *
 * @since 1.0.0
 */
class FlexibleSemanticVersionRule extends Simple {

	/**
	 * Validates a version string against flexible semantic version rules.
	 *
	 * @param mixed $input The version string to validate.
	 * @return bool True if the version is valid, false otherwise.
	 */
	public function isValid( mixed $input ): bool {
		if ( ! is_string( $input ) ) {
			return false;
		}
		$pattern = '/^[0-9]+(\.[0-9]+)?(\.[0-9]+)?$/';
		return 1 === preg_match( $pattern, $input );
	}
}
