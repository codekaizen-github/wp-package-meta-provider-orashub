<?php
/**
 * Assembler
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Array\PackageMeta\Response;
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Assembler\Array\PackageMeta\Response;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Array\PackageMeta\ResponsePackageMetaArrayAssemblerContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Exceptions\ValidationException;
use UnexpectedValueException;

/**
 * Class to assemble package meta from response
 */
class StandardResponsePackageMetaArrayAssembler implements ResponsePackageMetaArrayAssemblerContract {

	/**
	 * Undocumented variable
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
	 * @param string          $metaAnnotationKey Key to extract meta information from.
	 * @param LoggerInterface $logger Logger.
	 */
	public function __construct(
		string $metaAnnotationKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
		LoggerInterface $logger = new NullLogger()
	) {
		$this->metaAnnotationKey = $metaAnnotationKey;
		$this->logger            = $logger;
	}
	/**
	 * Values will have been validated
	 *
	 * @param ResponseInterface $response Response.
	 * @return array<string,mixed> $metaDecoded
	 * @throws UnexpectedValueException If the response cannot be decoded.
	 */
	public function assemble( ResponseInterface $response ): array {
		$responseStream  = $response->getBody();
		$responseDecoded = json_decode( $responseStream, true );
		if ( null === $responseDecoded ) {
			throw new UnexpectedValueException( 'Unable to decode JSON' );
		}
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Key(
						'annotations',
						new Rules\AllOf(
							new Rules\ArrayType(),
							new Rules\Key(
								$this->metaAnnotationKey,
								new Rules\StringType(),
							),
						)
					),
				)
			)->check( $responseDecoded );
		} catch ( ValidationException $e ) {
			$this->logger->error(
				'MetaAnnotationKeyAccessor raw validation failed',
				[
					'exception' => $e,
					'raw'       => $responseDecoded,
				]
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new UnexpectedValueException( $e->getMessage() );
		}
		/**
		 * Values will have been validated.
		 *
		 * @var array<string,mixed> $responseDecoded
		 * @var array<string,mixed> $annotations
		 */
		$annotations = $responseDecoded['annotations'];
		/**
		 * Value will have been validated.
		 *
		 * @var string $metaRaw
		 */
		$metaRaw     = $annotations[ $this->metaAnnotationKey ];
		$metaDecoded = json_decode( $metaRaw, true );
		try {
			Validator::create(
				new Rules\AllOf(
					new Rules\ArrayType(),
					new Rules\Call( 'array_keys', new Rules\Each( new Rules\StringType() ) )
				)
			)->check( $metaDecoded );
		} catch ( ValidationException $e ) {
			$this->logger->error(
				'MetaAnnotationKeyAccessor metaDecoded validation failed',
				[
					'exception'   => $e,
					'metaDecoded' => $metaDecoded,
					'metaRaw'     => $metaRaw,
				]
			);
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new UnexpectedValueException( $e->getMessage() );
		}
		/**
		 * Validated.
		 *
		 * @var array<string,mixed> $metaDecoded
		 */
		return $metaDecoded;
	}
}
