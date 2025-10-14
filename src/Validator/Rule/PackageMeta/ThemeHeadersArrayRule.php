<?php
/**
 * Theme Headers Array Validation Rule
 *
 * This file contains a validation rule for WordPress theme header arrays.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta
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
				new Rules\Key( 'name', new Rules\StringType(), true ),
				new Rules\Key( 'fullSlug', new Rules\StringType(), true ),
				new Rules\Key( 'shortSlug', new Rules\StringType(), true ),
				new Rules\Key( 'viewUrl', new Rules\AnyOf( new Rules\NullType(), new Rules\Url() ), false ),
				new Rules\Key(
					'version',
					new Rules\AnyOf(
						new Rules\NullType(),
						new FlexibleSemanticVersionRule()
					),
					false
				),
				new Rules\Key( 'downloadUrl', new Rules\AnyOf( new Rules\NullType(), new Rules\Url() ), false ),
				new Rules\Key(
					'tested',
					new Rules\AnyOf(
						new Rules\NullType(),
						new FlexibleSemanticVersionRule()
					),
					false
				),
				new Rules\Key(
					'stable',
					new Rules\AnyOf(
						new Rules\NullType(),
						new FlexibleSemanticVersionRule()
					),
					false
				),
				new Rules\Key(
					'tags',
					new Rules\AllOf(
						new Rules\ArrayType(),
						new Rules\Each( new Rules\StringType() )
					),
					false
				),
				new Rules\Key( 'author', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key( 'authorUrl', new Rules\AnyOf( new Rules\NullType(), new Rules\Url() ), false ),
				new Rules\Key( 'license', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key( 'licenseUrl', new Rules\AnyOf( new Rules\NullType(), new Rules\Url() ), false ),
				new Rules\Key( 'description', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key(
					'shortDescription',
					new Rules\AnyOf(
						new Rules\NullType(),
						new Rules\StringType()
					),
					false
				),
				new Rules\Key(
					'requiresWordPressVersion',
					new Rules\AnyOf(
						new Rules\NullType(),
						new FlexibleSemanticVersionRule()
					),
					false
				),
				new Rules\Key(
					'requiresPHPVersion',
					new Rules\AnyOf(
						new Rules\NullType(),
						new FlexibleSemanticVersionRule()
					),
					false
				),
				new Rules\Key( 'textDomain', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key( 'domainPath', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key( 'template', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
				new Rules\Key( 'status', new Rules\AnyOf( new Rules\NullType(), new Rules\StringType() ), false ),
			)
		)->isValid( $input );
	}
}
