<?php
/**
 * Interface for AssociativeArrayStringToStringAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor;

interface AssociativeArrayStringToStringAccessorContract {
	/**
	 * Gets data
	 *
	 * @return array<string,string>
	 */
	public function get(): array;
}
