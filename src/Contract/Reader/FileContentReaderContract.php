<?php
/**
 * File Content Reader Contract
 *
 * Defines the contract for reading file content.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocal
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract\Reader;

/**
 * Interface for file content readers.
 *
 * @since 1.0.0
 */
interface FileContentReaderContract {

	/**
	 * Reads content from a file.
	 *
	 * @param string $filePath Path to the file to read.
	 * @return string The content of the file.
	 */
	public function read( string $filePath ): string;
}
