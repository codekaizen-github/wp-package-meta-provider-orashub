<?php
/**
 * Local Plugin Package Meta Provider Service
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Service\Value\PackageMeta\Plugin;

// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Response\MixedArrayResponseAssemblerContract;
// phpcs:ignore Generic.Files.LineLength -- Keep import on one line.
use CodeKaizen\WPPackageMetaProviderORASHub\Value\PackageMeta\Plugin\StandardPluginPackageMetaValue;
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
class StandardPluginPackageMetaValueService implements PluginPackageMetaValueServiceContract {

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
	 * @var MixedArrayResponseAssemblerContract
	 */
	protected MixedArrayResponseAssemblerContract $assembler;

	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param RequestInterface                    $request Request.
	 * @param ClientInterface                     $client Client.
	 * @param MixedArrayResponseAssemblerContract $assembler Assembler.
	 * @param LoggerInterface                     $logger Logger.
	 */
	public function __construct(
		RequestInterface $request,
		ClientInterface $client,
		MixedArrayResponseAssemblerContract $assembler,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->request   = $request;
		$this->client    = $client;
		$this->assembler = $assembler;
		$this->logger    = $logger;
	}

	/**
	 * Creates a new StandardPluginPackageMetaValue instance.
	 *
	 * @return PluginPackageMetaValueContract
	 * @throws UnexpectedValueException If the metadata is invalid.
	 * @throws Throwable Throws exception on other errors.
	 */
	public function getPackageMeta(): PluginPackageMetaValueContract {
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
		return new StandardPluginPackageMetaValue( $assembled );
	}
}
