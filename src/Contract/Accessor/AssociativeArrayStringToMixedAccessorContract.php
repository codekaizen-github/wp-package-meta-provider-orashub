<?php
/**
 * Interface for MixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
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
