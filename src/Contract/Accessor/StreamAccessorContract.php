<?php
/**
 * Interface for AssociativeArrayStringToMixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor;

use Psr\Http\Message\StreamInterface;

interface StreamAccessorContract {
	/**
	 * Gets data
	 *
	 * @return StreamInterface
	 */
	public function get(): StreamInterface;
}
