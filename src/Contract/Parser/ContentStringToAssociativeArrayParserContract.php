<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser;

interface ContentStringToAssociativeArrayParserContract
{
    /**
     * @return array<string,string>
     */
    public function parse(string $content): array;
}
