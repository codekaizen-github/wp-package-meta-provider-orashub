<?php
/**
 * Factory for StandardThemePackageMetaValueService instances.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Response\MixedArray\StandardMixedArrayResponseAssembler;
use CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Undocumented class
 */
class StandardThemePackageMetaValueServiceFactory {
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
	 * Creates a new StandardThemePackageMetaValueService instance.
	 *
	 * @return ThemePackageMetaValueServiceContract
	 */
	public function create(): ThemePackageMetaValueServiceContract {
		$assembler = new StandardMixedArrayResponseAssembler(
			$this->metaAnnotationKey,
			$this->logger
		);
		$client    = new Client( $this->httpOptions );
		$request   = new Request( 'GET', $this->url );
		return new StandardThemePackageMetaValueService(
			$request,
			$client,
			$assembler,
			$this->logger
		);
	}
}
