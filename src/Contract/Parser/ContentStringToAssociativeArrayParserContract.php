<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser;

interface ContentStringToAssociativeArrayParserContract
{
    public function parse(string $content): array;
}
