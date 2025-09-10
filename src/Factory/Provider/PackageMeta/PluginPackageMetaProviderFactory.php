<?php
/**
 * Local Plugin Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Accessor\MetaAnnotationKeyAccessor;
use CodeKaizen\WPPackageMetaProviderORASHub\Client\GuzzleHttpGetRequest;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider;
use GuzzleHttp\Client;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract {
	/**
	 * URL to meta endpoint.
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * Key to extract metadata from.
	 *
	 * @var string
	 */
	protected string $metaAnnotationKey;

	/**
	 * Constructor.
	 *
	 * @param string $url Endpoint with meta information.
	 * @param string $metaAnnotationKey Key to extract meta information from.
	 */
	public function __construct( string $url, string $metaAnnotationKey ) {
		$this->url               = $url;
		$this->metaAnnotationKey = $metaAnnotationKey;
	}

	/**
	 * Creates a new PluginPackageMetaProvider instance.
	 *
	 * @return PluginPackageMetaContract
	 */
	public function create(): PluginPackageMetaContract {
		$client                    = new Client();
		$requestor                 = new GuzzleHttpGetRequest( $client, $this->url );
		$metaAnnotationKeyAccessor = new MetaAnnotationKeyAccessor( $requestor, $this->metaAnnotationKey );
		return new PluginPackageMetaProvider( $metaAnnotationKeyAccessor );
	}
}
