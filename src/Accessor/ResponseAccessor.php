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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use UnexpectedValueException;

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
	protected array $options;
	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Undocumented function
	 *
	 * @param Client              $client Client.
	 * @param string|UriInterface $uri URI.
	 * @param array<string,mixed> $options Options.
	 * @param LoggerInterface     $logger Logger.
	 * @throws UnexpectedValueException If URL is not valid.
	 */
	public function __construct(
		Client $client,
		string|UriInterface $uri,
		array $options = [],
		LoggerInterface $logger = new NullLogger()
	) {
		try {
			Validator::create( new Rules\Url() )->check( $uri );
		} catch ( ValidationException $e ) {
			throw new UnexpectedValueException( 'Invalid URL' );
		}
		$this->client  = $client;
		$this->uri     = $uri;
		$this->options = $options;
		$this->logger  = $logger;
	}
	/**
	 * Undocumented function
	 *
	 * @return ResponseInterface
	 */
	public function get(): ResponseInterface {
		$this->logger->debug(
			"HTTP GET Request {$this->uri}",
			[
				'uri'     => $this->uri,
				'options' => $this->options,
			]
		);
		$response = $this->client->get( $this->uri, $this->options );
		$this->logger->debug(
			"HTTP GET Response {$this->uri} {$response->getStatusCode()}",
			[
				'uri'          => $this->uri,
				'options'      => $this->options,
				'statusCode'   => $response->getStatusCode(),
				'reasonPhrase' => $response->getReasonPhrase(),
				'headers'      => $response->getHeaders(),
				'body'         => $response->getBody(),
			]
		);
		return $response;
	}
}
