<?php
/**
 * Local Plugin Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\AssociativeArrayStringToMixedAccessor;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\ResponseAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\StreamAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\JSONDecoder;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Factory\AssociativeArrayStringToMixedAccessorFactoryContract;
use GuzzleHttp\Client;
use Psr\Http\Message\UriInterface;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class PackageMetaHTTPJSONMetaAnnotationMixedAccessorAccessorFactory implements AssociativeArrayStringToMixedAccessorFactoryContract {
	/**
	 * Undocumented variable
	 *
	 * @var string|UriInterface
	 */
	protected string|UriInterface $uri;

	/**
	 * Key to extract metadata from.
	 *
	 * @var string
	 */
	protected string $metaAnnotationKey;

	/**
	 * Undocumented variable
	 *
	 * @var array<string,mixed>
	 */
	protected array $options = [];

	/**
	 * Constructor.
	 *
	 * @param string|UriInterface $uri URI.
	 * @param string              $metaAnnotationKey Key to extract meta information from.
	 * @param array<string,mixed> $options Options.
	 */
	public function __construct( string|UriInterface $uri, string $metaAnnotationKey, array $options = [] ) {
		$this->uri               = $uri;
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->options           = $options;
	}

	/**
	 * Creates a new PluginPackageMetaProvider instance.
	 *
	 * @return AssociativeArrayStringToMixedAccessorContract
	 */
	public function create(): AssociativeArrayStringToMixedAccessorContract {
		$client   = new Client();
		$response = new ResponseAccessor( $client, $this->uri, $this->options );
		$stream   = new StreamAccessor( $response );
		$json     = new JSONDecoder( $stream );
		return new MetaAnnotationKeyAccessor( $json, $this->metaAnnotationKey );
	}
}
