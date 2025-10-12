<?php
/**
 * Interface for AssociativeArrayStringToMixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor;

interface AssociativeArrayStringToMixedAccessorContract {
	/**
	 * Gets data
	 *
	 * @return array<string,mixed>
	 */
	public function get(): array;
}
