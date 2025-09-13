<?php
/**
 * Accessor interface.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Factory;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;

interface AssociativeArrayStringToMixedAccessorFactoryContract {

	/**
	 * Undocumented function
	 *
	 * @return AssociativeArrayStringToMixedAccessorContract
	 */
	public function create(): AssociativeArrayStringToMixedAccessorContract;
}
