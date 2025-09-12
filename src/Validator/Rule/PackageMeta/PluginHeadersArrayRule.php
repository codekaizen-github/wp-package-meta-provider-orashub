<?php
/**
 * Plugin Headers Array Validation Rule
 *
 * This file contains a validation rule for WordPress plugin header arrays.
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
				new Rules\Key( 'name', new Rules\StringType(), true ),
				new Rules\Key( 'viewUrl', new Rules\Url(), false ),
				new Rules\Key( 'version', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'downloadUrl', new Rules\Url(), false ),
				new Rules\Key( 'tested', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'stable', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key(
					'tags',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\StringType() )
					),
					false
				),
				new Rules\Key( 'author', new Rules\StringType(), false ),
				new Rules\Key( 'authorUrl', new Rules\Url(), false ),
				new Rules\Key( 'license', new Rules\StringType(), false ),
				new Rules\Key( 'licenseUrl', new Rules\Url(), false ),
				new Rules\Key( 'description', new Rules\StringType(), false ),
				new Rules\Key( 'shortDescription', new Rules\StringType(), false ),
				new Rules\Key( 'requiresWordPressVersion', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'requiresPHPVersion', new FlexibleSemanticVersionRule(), false ),
				new Rules\Key( 'textDomain', new Rules\StringType(), false ),
				new Rules\Key( 'domainPath', new Rules\StringType(), false ),
				new Rules\Key(
					'requiresPlugins',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\StringType() )
					),
					false
				),
				new Rules\Key(
					'sections',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\StringType() ),
						new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ),
					),
					false
				),
				new Rules\Key( 'network', new Rules\BoolType(), false ),
			)
		)->isValid( $input );
	}
}
