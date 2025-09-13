<?php
/**
 * Interface for AssociativeArrayStringToMixedAccessorContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor;

use Psr\Http\Message\ResponseInterface;

interface ResponseAccessorContract {
	/**
	 * Gets data
	 *
	 * @return ResponseInterface
	 */
	public function get(): ResponseInterface;
}
