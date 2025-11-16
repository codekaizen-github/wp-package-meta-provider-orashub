<?php
/**
 * Factory for ThemePackageMetaValueService instances.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Array\PackageMeta\ResponsePackageMetaArrayAssembler;
use CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\ThemePackageMetaValueService;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Undocumented class
 */
class ThemePackageMetaValueServiceFactoryV1 {
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
	 * @var array<string,mixed>
	 */
	protected array $httpOptions;

	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string              $url Endpoint with meta information.
	 * @param string              $metaAnnotationKey Key to extract meta information from.
	 * @param array<string,mixed> $httpOptions HTTP client options.
	 * @param LoggerInterface     $logger Logger.
	 */
	public function __construct(
		string $url,
		string $metaAnnotationKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
		array $httpOptions = [],
		LoggerInterface $logger = new NullLogger()
	) {
		$this->url               = $url;
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->httpOptions       = $httpOptions;
		$this->logger            = $logger;
	}
	/**
	 * Creates a new ThemePackageMetaValueService instance.
	 *
	 * @return ThemePackageMetaValueServiceContract
	 */
	public function create(): ThemePackageMetaValueServiceContract {
		$assembler = new ResponsePackageMetaArrayAssembler(
			$this->metaAnnotationKey,
			$this->logger
		);
		$client    = new Client( $this->httpOptions );
		$request   = new \GuzzleHttp\Psr7\Request( 'GET', $this->url );
		return new ThemePackageMetaValueService(
			$request,
			$client,
			$assembler,
			$this->logger
		);
	}
}
