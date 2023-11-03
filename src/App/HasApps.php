<?php

/**
 * User: Maros Jasan
 * Date: 5/13/2020
 * Time: 3:34 PM
 */

namespace Geodeticca\Iam\App;

trait HasApps
{
    /**
     * @var array
     */
    public array $apps = [];

    /**
     * @var array
     */
    public array $app_uniqids = [];

    /**
     * @var array
     */
    public array $connected_apps = [];

    /**
     * @param int $appId
     * @return $this
     */
    public function addApp(int $appId): self
    {
        $this->apps[] = $appId;

        return $this;
    }

    /**
     * @param string $appUniqid
     * @return $this
     */
    public function addAppUniqid(string $appUniqid): self
    {
        $this->app_uniqids[] = $appUniqid;

        return $this;
    }

    /**
     * @param string $connectedApp
     * @return $this
     */
    public function addConnectedApp(string $connectedApp): self
    {
        $this->connected_apps[] = $connectedApp;

        return $this;
    }

    /**
     * @return array
     */
    public function getApps(): array
    {
        return array_values(array_unique($this->apps));
    }

    /**
     * @return array
     */
    public function getAppUniqids(): array
    {
        return array_values(array_unique($this->app_uniqids));
    }

    /**
     * @return array
     */
    public function getConnectedApps(): array
    {
        return array_values(array_unique($this->connected_apps));
    }

    /**
     * @param string $appUniqid
     * @return bool
     */
    public function hasAppUniqid(string $appUniqid): bool
    {
        $appUniqids = $this->getAppUniqids();

        return in_array($appUniqid, $appUniqids);
    }

    /**
     * @param array $appIds
     * @return $this
     */
    public function setApps(array $appIds): self
    {
        $this->apps = array_values(array_unique(array_map('intval', $appIds)));

        return $this;
    }

    /**
     * @param array $appUniqids
     * @return $this
     */
    public function setAppUniqids(array $appUniqids): self
    {
        $this->app_uniqids = array_values(array_unique($appUniqids));

        return $this;
    }

    /**
     * @param array $connectedApps
     * @return $this
     */
    public function setConnectedApps(array $connectedApps): self
    {
        $this->connected_apps = array_values(array_unique($connectedApps));

        return $this;
    }

    /**
     * @param int $appId
     * @return bool
     */
    public function hasApp(int $appId): bool
    {
        return in_array($appId, $this->apps);
    }

    /**
     * @return bool
     */
    public function belongsToApp(): bool
    {
        return !empty($this->apps);
    }
}
