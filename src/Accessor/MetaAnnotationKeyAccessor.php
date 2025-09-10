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

/**
 * HTTPGetRequest.
 */
class MetaAnnotationKeyAccessor implements MixedAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var AssociativeArrayStringToMixedAccessorContract
	 */
	protected AssociativeArrayStringToMixedAccessorContract $accessor;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $metaAnnotationKey;
	/**
	 * Undocumented function
	 *
	 * @param AssociativeArrayStringToMixedAccessorContract $accessor Accessor.
	 * @param string                                        $metaAnnotationKey Meta Annotation Key.
	 */
	public function __construct( AssociativeArrayStringToMixedAccessorContract $accessor, string $metaAnnotationKey ) {
		$this->accessor          = $accessor;
		$this->metaAnnotationKey = $metaAnnotationKey;
	}
	/**
	 * Undocumented function
	 *
	 * @return mixed
	 * @throws Exception Throws exception on array key not existing.
	 */
	public function get(): mixed {
		$raw = $this->accessor->get();
		if ( ! array_key_exists( $this->metaAnnotationKey, $raw ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new Exception( "Array key does not exist: $this->metaAnnotationKey" );
		}
		return $raw[ $this->metaAnnotationKey ];
	}
}
