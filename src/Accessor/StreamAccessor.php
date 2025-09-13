<?php
/**
 * Class for accessing key from associative array
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\ResponseAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\StreamAccessorContract;
use Psr\Http\Message\StreamInterface;

/**
 * HTTPGetRequest.
 */
class StreamAccessor implements StreamAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var ResponseAccessorContract
	 */
	protected ResponseAccessorContract $client;
	/**
	 * Undocumented function
	 *
	 * @param ResponseAccessorContract $client Client.
	 */
	public function __construct( ResponseAccessorContract $client ) {
		$this->client = $client;
	}
	/**
	 * Undocumented function
	 *
	 * @return StreamInterface
	 */
	public function get(): StreamInterface {
		$response = $this->client->get();
		return $response->getBody();
	}
}
