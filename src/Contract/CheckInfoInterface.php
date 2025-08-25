<?php

namespace CodeKaizen\WPPackageAutoupdater\Contract;

interface CheckInfoInterface
{
    /**
     * Add our self-hosted description to the filter
     * This method is called by WordPress when displaying the plugin/theme information
     * in the admin UI (plugin details popup or theme details screen)
     *
     * @param bool $false
     * @param array $action The type of information being requested
     * @param object $arg The arguments passed to the API request
     * @return bool|object
     */
    public function checkInfo(bool $false, array $action, object $arg): bool|object;
}
