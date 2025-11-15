<?php
/**
 * HTTP Client interface.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Client
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriInterface;

use Psr\Http\Message\ResponseInterface;

/**
 * Undocumented interface
 */
interface HTTPClientContract extends ClientInterface {

	/**
	 * Create and send an HTTP request.
	 *
	 * Use an absolute path to override the base path of the client, or a
	 * relative path to append to the base path of the client. The URL can
	 * contain the query string as well.
	 *
	 * @param string              $method  HTTP method.
	 * @param string|UriInterface $uri     URI object or string.
	 * @param array<string,mixed> $options Request options to apply.
	 */
	public function request( string $method, string|UriInterface $uri, array $options = [] ): ResponseInterface;

	/**
	 * Create and send an HTTP GET request.
	 *
	 * Use an absolute path to override the base path of the client, or a
	 * relative path to append to the base path of the client. The URL can
	 * contain the query string as well.
	 *
	 * @param string|UriInterface $uri     URI object or string.
	 * @param array<string,mixed> $options Request options to apply.
	 */
	public function get( string|UriInterface $uri, array $options = [] ): ResponseInterface;
}
