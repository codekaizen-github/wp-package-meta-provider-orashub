<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckUpdateMetaFormatterInterface
{
    public function formatMetaForCheckUpdate(array $response, string $key): array;
}
