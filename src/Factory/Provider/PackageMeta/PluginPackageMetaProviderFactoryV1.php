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
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\PackageMeta\HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\PluginPackageMetaProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class PluginPackageMetaProviderFactoryV1 implements PluginPackageMetaProviderFactoryContract {
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
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string          $url Endpoint with meta information.
	 * @param string          $metaAnnotationKey Key to extract meta information from.
	 * @param LoggerInterface $logger Logger.
	 */
	public function __construct(
		string $url,
		string $metaAnnotationKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
		LoggerInterface $logger = new NullLogger()
	) {
		$this->url               = $url;
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->logger            = $logger;
	}

	/**
	 * Creates a new PluginPackageMetaProvider instance.
	 *
	 * @return PluginPackageMetaContract
	 */
	public function create(): PluginPackageMetaContract {
		$factory                   = new HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory(
			$this->url,
			$this->metaAnnotationKey,
			[],
			$this->logger,
		);
		$metaAnnotationKeyAccessor = $factory->create();
		return new PluginPackageMetaProvider( $metaAnnotationKeyAccessor );
	}
}
