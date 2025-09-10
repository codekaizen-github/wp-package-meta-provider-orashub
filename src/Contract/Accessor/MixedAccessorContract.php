<?php
/**
 * Interface for MixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
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
