<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Formatter;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoMetaFormatterInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\PackageMetaDetailsPluginInterface;
use stdClass;

class CheckInfoMetaFormatterPlugin implements CheckInfoMetaFormatterInterface
{
    private PackageMetaDetailsPluginInterface $provider;
    public function __construct(PackageMetaDetailsPluginInterface $provider)
    {
        $this->provider = $provider;
    }
    public function formatMetaForCheckInfo(): object
    {
        $stdObj = new stdClass();
        $stdObj->name = $this->provider->getName();
        $stdObj->slug = $this->provider->getShortSlug();
        $stdObj->version = $this->provider->getVersion();
        $stdObj->author = $this->provider->getAuthor();
        // $stdObj->author_profile
        $stdObj->requires = $this->provider->getRequiresWordPressVersion();
        $stdObj->tested = $this->provider->getTested();
        $stdObj->requires_php = $this->provider->getRequiresPHPVersion();
        $stdObj->homepage = $this->provider->getViewURL();
        $stdObj->download_link = $this->provider->getDownloadURL();
        $stdObj->update_uri = $this->provider->getDownloadURL();
        // $stdObj->last_updated
        $stdObj->sections = $this->provider->getSections();
        $stdObj->tags = $this->provider->getTags();
        // WordPress expects these properties for plugin information
        $stdObj->external = true; // indicates this is an external package
        return $stdObj;
    }
}
