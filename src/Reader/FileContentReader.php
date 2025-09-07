<?php
/**
 * File Content Reader
 *
 * Provides a way to read the contents of a file.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Reader;

use CodeKaizen\WPPackageMetaProviderORASHub\Contract\Reader\FileContentReaderContract;
use InvalidArgumentException;

/**
 * FileContentReader class implements the FileContentReaderContract interface.
 *
 * Reads content from files with size limits for performance.
 *
 * @since 1.0.0
 */
class FileContentReader implements FileContentReaderContract {

	/**
	 * Reads content from a file.
	 *
	 * @param string $filePath Path to the file to read.
	 * @return string The content of the file.
	 * @throws InvalidArgumentException If the file doesn't exist or is not readable.
	 */
	public function read( string $filePath ): string {
		if ( ! file_exists( $filePath ) || ! is_readable( $filePath ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message not displayed to end users.
			throw new InvalidArgumentException( "Invalid or inaccessible file path: $filePath" );
		}
		$kbInBytes = 1024;
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Not in WordPress context.
		$fileData = file_get_contents( $filePath, false, null, 0, 8 * $kbInBytes );
		if ( false === $fileData ) {
			$fileData = '';
		}
		return $fileData;
	}
}
