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
     * @param int $appId
     * @return $this
     */
    public function addApp(int $appId): self
    {
        $this->apps[] = $appId;

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
     * @param array $apps
     * @return $this
     */
    public function setApps(array $apps): self
    {
        $this->apps = array_values(array_unique(array_map('intval', $apps)));

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
