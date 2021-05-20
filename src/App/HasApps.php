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
    public $apps = [];

    /**
     * @param int $appId
     * @return $this
     */
    public function addApp($appId)
    {
        $this->apps[] = (int)$appId;

        return $this;
    }

    /**
     * @return array
     */
    public function getApps()
    {
        return array_values(array_unique($this->apps));
    }

    /**
     * @param array $apps
     * @return $this
     */
    public function setApps(array $apps)
    {
        $this->apps = array_values(array_unique(array_map('intval', $apps)));

        return $this;
    }

    /**
     * @param int $appId
     * @return bool
     */
    public function hasApp($appId)
    {
        return in_array($appId, $this->apps);
    }

    /**
     * @return bool
     */
    public function belongsToApp()
    {
        return !empty($this->apps);
    }
}
