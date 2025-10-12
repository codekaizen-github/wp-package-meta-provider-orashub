<?php
/**
 * Class for accessing key from associative array
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Accessor
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;
use PHP_CodeSniffer\Ruleset;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Exceptions\ValidationException;
use UnexpectedValueException;

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
	 * @throws UnexpectedValueException Throws exception on non-array, array key not existing, or invalid keys.
	 */
	public function get(): array {
		$raw = $this->accessor->get();
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Key(
						'annotations',
						new Rules\AllOf(
							new Rules\ArrayType(),
							new Rules\Key(
								$this->metaAnnotationKey,
								new Rules\StringType(),
							),
						)
					),
				)
			)->check( $raw );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new UnexpectedValueException( $e->getMessage() );
		}
		/**
		 * Values will have been validated.
		 *
		 * @var array<string,mixed> $raw
		 * @var array<string,mixed> $annotations
		 */
		$annotations = $raw['annotations'];
		/**
		 * Value will have been validated.
		 *
		 * @var string $metaRaw
		 */
		$metaRaw     = $annotations[ $this->metaAnnotationKey ];
		$metaDecoded = json_decode( $metaRaw, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) )
				)
			)->check( $metaDecoded );
		} catch ( ValidationException $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new UnexpectedValueException( $e->getMessage() );
		}
		/**
		 * Values will have been validated
		 *
		 * @var array<string,mixed> $metaDecoded
		 */
		return $metaDecoded;
	}
}
