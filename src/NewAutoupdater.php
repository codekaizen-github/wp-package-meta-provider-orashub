<?php
interface PreSetSiteTransientUpdateHookProviderInterface
{
    public function getLocalPackageSlug(): string;
    public function getLocalPackageVersion(): string;
    public function getRemotePackageVersion(): string;
    public function formatMetaForTransient(): object;
}
interface PreSetSiteTransientUpdateHookInterface
{
    public function handle(object $transient): object;
}
class PreSetSiteTransientPluginUpdateHookV1 implements PreSetSiteTransientUpdateHookInterface
{
    private PreSetSiteTransientUpdateHookProviderInterface $provider;
    private Psr\Log\LoggerInterface $logger;
    function __construct(PreSetSiteTransientUpdateHookProviderInterface $provider, Psr\Log\LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function handle(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response[$this->provider->getLocalPackageSlug()] = $this->provider->formatMetaForTransient();
            } else {
                $transient->no_update[$this->provider->getLocalPackageSlug()] = $this->provider->formatMetaForTransient();
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
class PreSetSiteTransientThemeUpdateHookV1 implements PreSetSiteTransientUpdateHookInterface
{
    private PreSetSiteTransientUpdateHookProviderInterface $provider;
    private Psr\Log\LoggerInterface $logger;
    function __construct(PreSetSiteTransientUpdateHookProviderInterface $provider, Psr\Log\LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }
    public function handle(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->provider->getLocalPackageSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->provider->getLocalPackageVersion(), $this->provider->getRemotePackageVersion(), '<')) {
                $transient->response[$this->provider->getLocalPackageSlug()] = (array) $this->provider->formatMetaForTransient();
            } else {
                $transient->no_update[$this->provider->getLocalPackageSlug()] = (array) $this->provider->formatMetaForTransient();
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
interface PackageMetaForUpdateCheckProviderInterface
{
    public function getShortSlug(): string;
    public function getFullSlug(): string;
    public function getVersion(): string;
}
interface PackageMetaForDetailsProviderInterface extends PackageMetaForUpdateCheckProviderInterface
{
    public function getDownloadURL(): string;
    public function getName(): ?string;
    public function getViewURL(): ?string;
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
    /** @return string */
    public function getRequiresPlugins(): array;
}
interface PackageMetaForDetailsThemeProviderInterface extends PackageMetaForDetailsProviderInterface {}
interface PackageMetaForDetailsPluginProviderInterface extends PackageMetaForDetailsProviderInterface
{
    public function getPluginFile(): string;
    public function getSections(): array;
    /** @return array<string,string> */
    public function getNetwork(): bool;
}
interface PackageMetaProviderInterface extends PackageMetaProviderInterface {}
interface FormatMetaForTransientProviderInterface
{
    public function formatMetaForTransient(): object;
}
class PreSetSiteTransientUpdateHookProviderV1 implements PreSetSiteTransientUpdateHookProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForUpdateCheckProviderInterface $remotePackageMetaProvider;
    private FormatMetaForTransientProviderInterface $formatMetaForTransientProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForUpdateCheckProviderInterface $remotePackageMetaProvider, FormatMetaForTransientProviderInterface $formatMetaForTransientProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
        $this->formatMetaForTransientProvider = $formatMetaForTransientProvider;
    }
    public function getLocalPackageSlug(): string
    {
        return $this->localPackageMetaProvider->getFullSlug();
    }
    public function getLocalPackageVersion(): string
    {
        return $this->localPackageMetaProvider->getVersion();
    }
    public function getRemotePackageVersion(): string
    {
        return $this->remotePackageMetaProvider->getVersion();
    }
    public function formatMetaForTransient(): object
    {
        // formatter will also have joint access to localPackageMetaProvider and remotePackageMetaProvider
        return $this->formatMetaForTransientProvider->formatMetaForTransient();
    }
}
class FormatMetaForTransientProviderPluginV1 implements FormatMetaForTransientProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForDetailsPluginProviderInterface $remotePackageMetaProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForDetailsPluginProviderInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function formatMetaForTransient(): object
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->localPackageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->remotePackageMetaProvider->getVersion();
        $metaObject->package = $this->remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $this->remotePackageMetaProvider->getViewURL();
        return $metaObject;
    }
}
class FormatMetaForTransientProviderThemeV1 implements FormatMetaForTransientProviderInterface
{
    private PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider;
    private PackageMetaForDetailsThemeProviderInterface $remotePackageMetaProvider;
    public function __construct(PackageMetaForUpdateCheckProviderInterface $localPackageMetaProvider, PackageMetaForDetailsThemeProviderInterface $remotePackageMetaProvider)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
    }
    public function formatMetaForTransient(): object
    {
        $metaObject = new stdClass();
        $metaObject->slug = $this->localPackageMetaProvider->getShortSlug();
        $metaObject->new_version = $this->remotePackageMetaProvider->getVersion();
        $metaObject->package = $this->remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $this->remotePackageMetaProvider->getViewURL();
        return $metaObject;
    }
}
