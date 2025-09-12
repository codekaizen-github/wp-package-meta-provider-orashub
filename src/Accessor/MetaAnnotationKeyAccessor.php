<?php
/**
 * Class for accessing key from associative array
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;
use Exception;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Exceptions\ValidationException;

/**
 * HTTPGetRequest.
 */
class MetaAnnotationKeyAccessor implements AssociativeArrayStringToMixedAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var MixedAccessorContract
	 */
	protected MixedAccessorContract $accessor;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $metaAnnotationKey;
	/**
	 * Undocumented function
	 *
	 * @param MixedAccessorContract $accessor Accessor.
	 * @param string                $metaAnnotationKey Meta Annotation Key.
	 */
	public function __construct( MixedAccessorContract $accessor, string $metaAnnotationKey ) {
		$this->accessor          = $accessor;
		$this->metaAnnotationKey = $metaAnnotationKey;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,mixed>
	 * @throws Exception|ValidationException Throws exception on non-array, array key not existing, or invalid keys.
	 */
	public function get(): array {
		$raw = $this->accessor->get();
		if ( ! is_array( $raw ) ) {
			throw new Exception( 'Input is not an array' );
		}
		if ( ! array_key_exists( $this->metaAnnotationKey, $raw ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new Exception( "Array key does not exist: $this->metaAnnotationKey" );
		}
		$value = $raw[ $this->metaAnnotationKey ];
		Validator::create( new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) ) )->check( $value );
		/**
		 * Value will have been validated
		 *
		 * @var array<string,mixed> $value
		 */
		return $value;
	}
}
