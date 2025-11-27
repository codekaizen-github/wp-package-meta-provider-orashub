<?php
/**
 * Interface for MixedArrayResponseAssemblerContract
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Response
 */

namespace CodeKaizen\WPPackageMetaProviderORASHub\Contract\Assembler\Response;

use Psr\Http\Message\ResponseInterface;

interface MixedArrayResponseAssemblerContract {
	/**
	 * Assembles package meta array from response.
	 *
	 * @param ResponseInterface $response Response.
	 * @return array<string,mixed>
	 */
	public function assemble( ResponseInterface $response ): array;
}
