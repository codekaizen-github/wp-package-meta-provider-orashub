<?php
/**
 * Interface for MixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor;

interface MixedAccessorContract {
	/**
	 * Gets data
	 *
	 * @return mixed
	 */
	public function get(): mixed;
}
