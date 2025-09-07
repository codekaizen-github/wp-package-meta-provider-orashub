<?php
/**
 * Content String To Associative Array Parser Contract
 *
 * Defines the contract for parsing content strings into associative arrays.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Parser;

/**
 * Interface for content string to associative array parsers.
 *
 * @since 1.0.0
 */
interface ContentStringToAssociativeArrayParserContract {

	/**
	 * Parses a content string into an associative array.
	 *
	 * @param string $content The content string to parse.
	 * @return array<string,string> Associative array of parsed content.
	 */
	public function parse( string $content ): array;
}
