<?php
/**
 * Class for making HTTPGetRequestJSONResponseGuzzleClients
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\StreamAccessorContract;
use UnexpectedValueException;

/**
 * HTTPGetRequestJSONResponseGuzzleClient.
 */
class JSONDecoder implements MixedAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var StreamAccessorContract
	 */
	protected StreamAccessorContract $client;
	/**
	 * Undocumented function
	 *
	 * @param StreamAccessorContract $client StreamAccessorContract.
	 */
	public function __construct( StreamAccessorContract $client ) {
		$this->client = $client;
	}
	/**
	 * Undocumented function
	 *
	 * @return mixed
	 * @throws UnexpectedValueException  Exception.
	 */
	public function get(): mixed {
		$body    = $this->client->get();
		$decoded = json_decode( $body, true );
		if ( null === $decoded ) {
			throw new UnexpectedValueException( 'Unable to decode JSON' );
		}
		return $decoded;
	}
}
