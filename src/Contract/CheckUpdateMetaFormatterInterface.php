<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Contract;

interface CheckUpdateMetaFormatterInterface
{
    public function formatMetaForCheckUpdate(array $response, string $key): array;
}
