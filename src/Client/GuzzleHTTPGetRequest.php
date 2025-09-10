<?php
/**
 * Class for making GuzzleHttpGetRequests
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Client;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\UriInterface;
use Respect\Validation\Rules\Url;
use Respect\Validation\Validator;

/**
 * GuzzleHttpGetRequest.
 */
class GuzzleHttpGetRequest implements AssociativeArrayStringToMixedAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var Client
	 */
	protected Client $client;
	/**
	 * Undocumented variable
	 *
	 * @var string|UriInterface
	 */
	protected string|UriInterface $uri;
	/**
	 * Undocumented variable
	 *
	 * @var array<string,mixed>
	 */
	protected array $options = [];
	/**
	 * Undocumented function
	 *
	 * @param Client              $client Client.
	 * @param string|UriInterface $uri URI.
	 * @param array<string,mixed> $options Options.
	 */
	public function __construct( Client $client, string|UriInterface $uri, array $options = [] ) {
		Validator::create( new Url() )->check( $uri );
		$this->client  = $client;
		$this->uri     = $uri;
		$this->options = $options;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,mixed>
	 * @throws Exception  Exception.
	 */
	public function get(): array {
		$response = $this->client->get( $this->uri, $this->options );
		$body     = $response->getBody();
		$decoded  = json_decode( $body, true );
		if ( ! is_array( $decoded ) ) {
			throw new Exception( 'Not of type array' );
		}
		/**
		 * Meta array will have been validated.
		 *
		 * @var array<string,mixed> $decoded
		 * */
		return $decoded;
	}
}
