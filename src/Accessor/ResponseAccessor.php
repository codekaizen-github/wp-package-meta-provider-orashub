<?php
/**
 * Class for accessing key from associative array
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Accessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\ResponseAccessorContract;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Respect\Validation\Validator;
use Respect\Validation\Rules;

/**
 * HTTPGetRequest.
 */
class ResponseAccessor implements ResponseAccessorContract {
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
		Validator::create( new Rules\Url() )->check( $uri );
		$this->client  = $client;
		$this->uri     = $uri;
		$this->options = $options;
	}
	/**
	 * Undocumented function
	 *
	 * @return ResponseInterface
	 */
	public function get(): ResponseInterface {
		$response = $this->client->get( $this->uri, $this->options );
		return $response;
	}
}
