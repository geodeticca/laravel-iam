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
