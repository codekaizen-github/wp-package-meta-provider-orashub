<?php
/**
 * Local Plugin Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Accessor\AssociativeArrayStringToMixedAccessorContract;

use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\ResponseAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\StreamAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\JSONDecoder;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Factory\AssociativeArrayStringToMixedAccessorFactoryContract;
use GuzzleHttp\Client;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
// phpcs:ignore Generic.Files.LineLength.TooLong, Squiz.Commenting.ClassComment.Missing
class HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory implements AssociativeArrayStringToMixedAccessorFactoryContract {

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
	protected array $options;

	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string|UriInterface $uri URI.
	 * @param string              $metaAnnotationKey Key to extract meta information from.
	 * @param array<string,mixed> $options Options.
	 * @param LoggerInterface     $logger Logger.
	 */
	public function __construct(
		string|UriInterface $uri,
		string $metaAnnotationKey,
		array $options = [],
		LoggerInterface $logger = new NullLogger()
	) {
		$this->uri               = $uri;
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->options           = $options;
		$this->logger            = $logger;
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
		return new MetaAnnotationKeyAccessor( $json, $this->metaAnnotationKey, $this->logger );
	}
}
