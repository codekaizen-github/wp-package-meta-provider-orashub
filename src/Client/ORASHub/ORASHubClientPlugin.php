<?php

namespace CodeKaizen\WPPackageAutoupdater\Client\ORASHub;


use CodeKaizen\WPPackageAutoupdater\Contract\RemoteClientInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\RemoteClientPluginInterface;
use CodeKaizen\WPPackageAutoupdater\Contract\PackageMetaDetailsPluginInterface;
use Respect\Validation\Validator;
use Respect\Validation\Rules;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Model\ORASHubPackageMetaFromObjectPlugin;
use Exception;

class ORASHubClientPlugin implements RemoteClientInterface, RemoteClientPluginInterface
{
    private string $baseURL;
    private string $metaAnnotationKey;
    public function __construct(string $baseURL, string $metaAnnotationKey)
    {
        Validator::create(new Rules\Url())->check($baseURL);
        $this->baseURL = $baseURL;
        $this->metaAnnotationKey = $metaAnnotationKey;
    }
    protected function getMetaURL()
    {
        return $this->baseURL . 'manifest';
    }
    public function getPackageMeta(): PackageMetaDetailsPluginInterface
    {
        $request = wp_remote_get($this->getMetaURL());

        if (is_wp_error($request)) {
            throw new Exception('Request to ' . $this->getMetaURL() . ' failed with error ' . $request->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($request);
        if ($status_code !== 200) {
            throw new Exception('Request to ' . $this->getMetaURL() . ' failed with status code ' . $status_code);
        }

        $body = wp_remote_retrieve_body($request);
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new Exception('Invalid JSON returned by request to ' . $this->getMetaURL());
        }
        if (!isset($json['annotations'][$this->metaAnnotationKey])) {
            throw new Exception('Meta annotation key is unset' . $this->metaAnnotationKey);
        }
        $meta_json = $json['annotations'][$this->metaAnnotationKey];
        $meta = json_decode($meta_json, false);
        return new ORASHubPackageMetaFromObjectPlugin($meta);
    }
}
