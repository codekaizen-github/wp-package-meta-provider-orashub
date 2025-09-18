<?php
/**
 * Local Theme Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Accessor\PackageMeta\HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory;
use CodeKaizen\WPPackageMetaProviderORASHub\Provider\PackageMeta\ThemePackageMetaProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Factory for creating local theme package meta providers.
 *
 * @since 1.0.0
 */
class ThemePackageMetaProviderFactoryV1 implements ThemePackageMetaProviderFactoryContract {
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
	public function __construct( string $url, string $metaAnnotationKey, LoggerInterface $logger = new NullLogger() ) {
		$this->url               = $url;
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->logger            = $logger;
	}

	/**
	 * Creates a new ThemePackageMetaProvider instance.
	 *
	 * @return ThemePackageMetaContract
	 */
	public function create(): ThemePackageMetaContract {
		$factory                   = new HTTPJSONMetaAnnotationKeyPackageMetaAccessorFactory(
			$this->url,
			$this->metaAnnotationKey,
			[],
			$this->logger,
		);
		$metaAnnotationKeyAccessor = $factory->create();
		return new ThemePackageMetaProvider( $metaAnnotationKeyAccessor );
	}
}
