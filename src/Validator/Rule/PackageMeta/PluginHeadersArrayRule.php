<?php
/**
 * Plugin Headers Array Validation Rule
 *
 * This file contains a validation rule for WordPress plugin header arrays.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version\FlexibleSemanticVersionRule;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

/**
 * Validation rule for WordPress plugin header arrays.
 *
 * Validates that a plugin header array contains the required fields
 * and that each field is of the expected type.
 *
 * @since 1.0.0
 */
class PluginHeadersArrayRule extends Simple {

	/**
	 * Validates a WordPress plugin header array.
	 *
	 * Checks that the input is an array and contains all required
	 * header fields with proper values.
	 *
	 * @param mixed $input The plugin header array to validate.
	 * @return bool True if validation passes, false otherwise.
	 */
	public function isValid( mixed $input ): bool {
		if ( ! is_array( $input ) ) {
			return false;
		}

		return Validator::create(
			new Rules\AllOf(
				new Rules\Key( 'Name', new Rules\StringType(), true ),
				new Rules\Key( 'PluginURI', new Rules\Url(), false ),
				new Rules\Key( 'Version', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'Description', new Rules\StringType(), false ),
				new Rules\Key( 'Author', new Rules\StringType(), false ),
				new Rules\Key( 'AuthorURI', new Rules\StringType(), false ),
				new Rules\Key( 'TextDomain', new Rules\StringType(), false ),
				new Rules\Key( 'DomainPath', new Rules\StringType(), false ),
				new Rules\Key( 'Network', new Rules\BoolVal(), false ),
				new Rules\Key( 'RequiresWP', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'RequiresPHP', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'UpdateURI', new Rules\Url(), false ),
				new Rules\Key( 'RequiresPlugins', new Rules\StringType(), false ),
			)
		)->isValid( $input );
	}
}
