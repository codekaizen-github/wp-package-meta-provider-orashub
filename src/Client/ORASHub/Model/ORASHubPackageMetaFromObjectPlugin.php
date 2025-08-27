<?php

namespace CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Model;

use Respect\Validation\Validator;
use CodeKaizen\WPPackageAutoupdater\Contract\PackageMetaDetailsPluginInterface;
use CodeKaizen\WPPackageAutoupdater\Client\ORASHub\Validation\Rule\ORASHubPackageMetaRulePlugin;
use JsonSerializable;

class ORASHubPackageMetaFromObjectPlugin implements PackageMetaDetailsPluginInterface, JsonSerializable
{
    private object $stdObj;
    public function __construct(object $stdObj)
    {
        Validator::create(new ORASHubPackageMetaRulePlugin())->check($stdObj);
        $this->stdObj = $stdObj;
    }
    public function getShortSlug(): string
    {
        return $this->stdObj->shortSlug;
    }
    public function getFullSlug(): string
    {
        return $this->stdObj->fullSlug;
    }
    public function getVersion(): string
    {
        return $this->stdObj->version;
    }
    public function getDownloadURL(): string
    {
        return $this->stdObj->downloadURL;
    }
    public function getName(): ?string
    {
        return $this->stdObj->name;
    }
    public function getViewURL(): ?string
    {
        return $this->stdObj->viewURL;
    }
    public function getTested(): ?string
    {
        return $this->stdObj->tested;
    }
    public function getStable(): ?string
    {
        return $this->stdObj->stable;
    }
    public function getTags(): array
    {
        return $this->stdObj->tags ?? [];
    }
    public function getAuthor(): ?string
    {
        return $this->stdObj->author;
    }
    public function getAuthorURL(): ?string
    {
        return $this->stdObj->authorURL;
    }
    public function getLicense(): ?string
    {
        return $this->stdObj->license;
    }
    public function getLicenseURL(): ?string
    {
        return $this->stdObj->licenseURL;
    }
    public function getShortDescription(): ?string
    {
        return $this->stdObj->shortDescription;
    }
    public function getDescription(): ?string
    {
        return $this->stdObj->description;
    }
    public function getRequiresWordPressVersion(): ?string
    {
        return $this->stdObj->requiresWordPressVersion;
    }
    public function getRequiresPHPVersion(): ?string
    {
        return $this->stdObj->requiresPHPVersion;
    }
    public function getTextDomain(): ?string
    {
        return $this->stdObj->textDomain;
    }
    public function getDomainPath(): ?string
    {
        return $this->stdObj->domainPath;
    }
    public function getRequiresPlugins(): array
    {
        return $this->stdObj->requiresPlugins ?? [];
    }
    public function getPluginFile(): string
    {
        return $this->stdObj->pluginFile;
    }
    public function getSections(): array
    {
        return $this->stdObj->sections ?? [];
    }
    public function getNetwork(): bool
    {
        return $this->stdObj->network;
    }
    public function jsonSerialize(): mixed
    {
        return $this->stdObj;
    }
}
