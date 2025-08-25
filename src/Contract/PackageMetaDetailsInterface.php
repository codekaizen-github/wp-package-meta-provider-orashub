<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface PackageMetaDetailsInterface extends CheckUpdateProviderRemotePackageMetaInterface
{
    public function getName(): ?string;
    public function getTested(): ?string;
    public function getStable(): ?string;
    /** @return string[] */
    public function getTags(): array;
    public function getAuthor(): ?string;
    public function getAuthorURL(): ?string;
    public function getLicense(): ?string;
    public function getLicenseURL(): ?string;
    public function getShortDescription(): ?string;
    public function getDescription(): ?string;
    public function getRequiresWordPressVersion(): ?string;
    public function getRequiresPHPVersion(): ?string;
    public function getTextDomain(): ?string;
    public function getDomainPath(): ?string;
    /** @return string[] */
    public function getRequiresPlugins(): array;
}
