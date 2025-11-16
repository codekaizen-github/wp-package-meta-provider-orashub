<?php
/**
 * Interface for ResponsePackageMetaArrayAssemblerContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Array\PackageMeta
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Array\PackageMeta;

use Psr\Http\Message\ResponseInterface;

interface ResponsePackageMetaArrayAssemblerContract {
	/**
	 * Assembles package meta array from response.
	 *
	 * @param ResponseInterface $response Response.
	 * @return array<string,mixed>
	 */
	public function assemble( ResponseInterface $response ): array;
}
