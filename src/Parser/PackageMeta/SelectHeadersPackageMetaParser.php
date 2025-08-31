<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Parser\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\ContentStringToAssociativeArrayParserContract;
use Exception;

class SelectHeadersPackageMetaParser implements ContentStringToAssociativeArrayParserContract
{
    /** @var array<string,string> */
    private array $headers;
    /**
     * @param array<string,string> $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }
    /**
     * Parse the file contents to retrieve its metadata.
     *
     * Searches for metadata for a file, such as a plugin or theme.  Each piece of
     * metadata must be on its own line. For a field spanning multiple lines, it
     * must not have any newlines or only parts of it will be displayed.
     *
     * @return array<string, string>
     */
    public function parse(string $content): array
    {

        // Make sure we catch CR-only line endings.
        $content = str_replace("\r", "\n", $content);
        $all_headers = [];

        foreach ($this->headers as $field => $regex) {
            if (preg_match('/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $content, $match) && $match[1]) {
                $all_headers[$field] = $this->cleanUpHeaderComment($match[1]);
            } else {
                $all_headers[$field] = '';
            }
        }

        return $all_headers;
    }
    /**
     * Strips close comment and close php tags from file headers used by WP.
     *
     * @param string $str Header comment to clean up.
     * @return string
     */
    protected function cleanUpHeaderComment($str)
    {
        $replaced = preg_replace('/\s*(?:\*\/|\?>).*/', '', $str);
        if (!is_string($replaced)) {
            throw new Exception("Invalid header value. Value must be string.");
        }
        return trim($replaced);
    }
}
