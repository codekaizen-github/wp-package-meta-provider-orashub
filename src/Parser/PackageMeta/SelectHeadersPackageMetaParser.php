<?php
/**
 * Select Headers Package Meta Parser
 *
 * Parses file content to extract metadata from headers based on regex patterns.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Parser\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\ContentStringToAssociativeArrayParserContract;
use Exception;

/**
 * Class for parsing package metadata from headers in file content.
 *
 * @since 1.0.0
 */
class SelectHeadersPackageMetaParser implements ContentStringToAssociativeArrayParserContract {

	/**
	 * Headers to extract from content.
	 *
	 * @var array<string,string>
	 */
	private array $headers;

	/**
	 * Constructor.
	 *
	 * @param array<string,string> $headers Map of header field names to regex patterns.
	 */
	public function __construct( array $headers ) {
		$this->headers = $headers;
	}

	/**
	 * Parse the file contents to retrieve its metadata.
	 *
	 * Searches for metadata for a file, such as a plugin or theme.  Each piece of
	 * metadata must be on its own line. For a field spanning multiple lines, it
	 * must not have any newlines or only parts of it will be displayed.
	 *
	 * @param string $content The content to parse.
	 * @return array<string, string> Extracted header values.
	 */
	public function parse( string $content ): array {

		// Make sure we catch CR-only line endings.
		$content    = str_replace( "\r", "\n", $content );
		$allHeaders = [];

		foreach ( $this->headers as $field => $regex ) {
			$pattern = '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi';
			if ( preg_match( $pattern, $content, $match ) && $match[1] ) {
				$allHeaders[ $field ] = $this->cleanUpHeaderComment( $match[1] );
			}
		}

		return $allHeaders;
	}
	/**
	 * Strips close comment and close php tags from file headers used by WP.
	 *
	 * @param string $str Header comment to clean up.
	 * @return string Cleaned header comment.
	 * @throws Exception If the header value is not a string.
	 */
	protected function cleanUpHeaderComment( $str ) {
		$replaced = preg_replace( '/\s*(?:\*\/|\?>).*/', '', $str );
		if ( ! is_string( $replaced ) ) {
			throw new Exception( 'Invalid header value. Value must be string.' );
		}
		return trim( $replaced );
	}
}
