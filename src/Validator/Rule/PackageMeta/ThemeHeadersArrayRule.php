<?php
/**
 * Theme Headers Array Validation Rule
 *
 * This file contains a validation rule for WordPress theme header arrays.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\Version\FlexibleSemanticVersionRule;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

/**
 * Validation rule for WordPress theme header arrays.
 *
 * Validates that a theme header array contains the required fields
 * and that each field is of the expected type.
 *
 * @since 1.0.0
 */
class ThemeHeadersArrayRule extends Simple {

	/**
	 * Validates a WordPress theme header array.
	 *
	 * Checks that the input is an array and contains all required
	 * header fields with proper values.
	 *
	 * @param mixed $input The theme header array to validate.
	 * @return bool True if validation passes, false otherwise.
	 */
	public function isValid( mixed $input ): bool {
		if ( ! is_array( $input ) ) {
			return false;
		}

		return Validator::create(
			new Rules\AllOf(
				new Rules\Key( 'Name', new Rules\StringType(), true ),
				new Rules\Key( 'ThemeURI', new Rules\Url(), false ),
				new Rules\Key( 'Description', new Rules\StringType(), false ),
				new Rules\Key( 'Author', new Rules\StringType(), false ),
				new Rules\Key( 'AuthorURI', new Rules\StringType(), false ),
				new Rules\Key( 'Version', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'Template', new Rules\StringType(), false ),
				new Rules\Key( 'Status', new Rules\StringType(), false ),
				new Rules\Key( 'Tags', new Rules\StringType(), false ),
				new Rules\Key( 'TextDomain', new Rules\StringType(), false ),
				new Rules\Key( 'DomainPath', new Rules\StringType(), false ),
				new Rules\Key( 'RequiresWP', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'RequiresPHP', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'UpdateURI', new Rules\Url(), false ),
			)
		)->isValid( $input );
	}
}
