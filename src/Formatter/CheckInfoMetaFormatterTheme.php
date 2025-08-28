<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Formatter;

use CodeKaizen\WPPackageMetaProviderLocal\Contract\CheckInfoMetaFormatterInterface;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\PackageMetaDetailsThemeInterface;
use stdClass;

class CheckInfoMetaFormatterTheme implements CheckInfoMetaFormatterInterface
{
    private PackageMetaDetailsThemeInterface $provider;
    public function __construct(PackageMetaDetailsThemeInterface $provider)
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
        $stdObj->tags = $this->provider->getTags();
        return $stdObj;
    }
}
