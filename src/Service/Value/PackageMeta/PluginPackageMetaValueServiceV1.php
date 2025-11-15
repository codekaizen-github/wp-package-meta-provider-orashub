<?php
/**
 * Local Plugin Package Meta Provider Factory
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta;

// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Array\PackageMeta\ResponsePackageMetaArrayAssemblerContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Client\HTTPClientContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Service\PackageMeta\PluginPackageMetaValue;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UnexpectedValueException;
use Throwable;

/**
 * Factory for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class PluginPackageMetaValueServiceV1 implements PluginPackageMetaValueServiceContract {

	/**
	 * Client.
	 *
	 * @var HTTPClientContract
	 */
	protected HTTPClientContract $client;

	/**
	 * Assembler.
	 *
	 * @var ResponsePackageMetaArrayAssemblerContract
	 */
	protected ResponsePackageMetaArrayAssemblerContract $assembler;

	/**
	 * URL to meta endpoint.
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Undocumented variable
	 *
	 * @var PluginPackageMetaValueContract|null
	 */
	protected ?PluginPackageMetaValueContract $value;

	/**
	 * Constructor.
	 *
	 * @param HTTPClientContract                        $client HTTP Client.
	 * @param ResponsePackageMetaArrayAssemblerContract $assembler Assembler.
	 * @param string                                    $url Endpoint with meta information.
	 * @param LoggerInterface                           $logger Logger.
	 */
	public function __construct(
		HTTPClientContract $client,
		ResponsePackageMetaArrayAssemblerContract $assembler,
		string $url,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->client    = $client;
		$this->assembler = $assembler;
		$this->url       = $url;
		$this->logger    = $logger;
		$this->value     = null;
	}

	/**
	 * Creates a new PluginPackageMetaValue instance.
	 *
	 * @return PluginPackageMetaValueContract
	 * @throws UnexpectedValueException If the metadata is invalid.
	 * @throws Throwable Throws exception on other errors.
	 */
	public function getPackageMeta(): PluginPackageMetaValueContract {
		if ( null !== $this->value ) {
			return $this->value;
		}
		$this->logger->debug(
			"HTTP GET Request {$this->url}",
			[
				'url' => $this->url,
			]
		);
		$response = $this->client->get( $this->url );
		$this->logger->debug(
			"HTTP GET Response {$this->url} {$response->getStatusCode()}",
			[
				'url'          => $this->url,
				'statusCode'   => $response->getStatusCode(),
				'reasonPhrase' => $response->getReasonPhrase(),
				'headers'      => $response->getHeaders(),
				'body'         => $response->getBody(),
			]
		);
		$assembled = $this->assembler->assemble( $response );
		return new PluginPackageMetaValue( $assembled );
	}
}
