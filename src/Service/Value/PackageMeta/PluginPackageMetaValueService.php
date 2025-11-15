<?php
/**
 * Local Plugin Package Meta Provider Service
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
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Value\PackageMeta\PluginPackageMetaValue;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UnexpectedValueException;
use Throwable;

/**
 * Service for creating local plugin package meta providers.
 *
 * @since 1.0.0
 */
class PluginPackageMetaValueService implements PluginPackageMetaValueServiceContract {

	/**
	 * Client.
	 *
	 * @var ClientInterface
	 */
	protected ClientInterface $client;

	/**
	 * Request.
	 *
	 * @var RequestInterface
	 */
	protected RequestInterface $request;

	/**
	 * Assembler.
	 *
	 * @var ResponsePackageMetaArrayAssemblerContract
	 */
	protected ResponsePackageMetaArrayAssemblerContract $assembler;

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
	 * @param RequestInterface                          $request Request.
	 * @param ClientInterface                           $client Client.
	 * @param ResponsePackageMetaArrayAssemblerContract $assembler Assembler.
	 * @param LoggerInterface                           $logger Logger.
	 */
	public function __construct(
		RequestInterface $request,
		ClientInterface $client,
		ResponsePackageMetaArrayAssemblerContract $assembler,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->request   = $request;
		$this->client    = $client;
		$this->assembler = $assembler;
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
			"HTTP {$this->request->getMethod()} Request {$this->request->getUri()}",
			[
				'url' => $this->request->getUri(),
			]
		);
		$response = $this->client->sendRequest( $this->request );
		$this->logger->debug(
			"HTTP {$this->request->getMethod()} Response {$this->request->getUri()} {$response->getStatusCode()}",
			[
				'url'          => $this->request->getUri(),
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
