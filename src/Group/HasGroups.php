<?php

/**
 * User: Maros Jasan
 * Date: 5/13/2020
 * Time: 3:34 PM
 */

namespace Geodeticca\Iam\Group;

trait HasGroups
{
    /**
     * @var array
     */
    public $groups = [];

    /**
     * @param int $groupId
     * @return $this
     */
    public function addGroup($groupId)
    {
        $this->groups[] = (int)$groupId;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return array_values(array_unique($this->groups));
    }

    /**
     * @param array $groups
     * @return $this
     */
    public function setGroups(array $groups)
    {
        $this->groups = array_values(array_unique(array_map('intval', $groups)));

        return $this;
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public function hasGroup($groupId)
    {
        return in_array($groupId, $this->groups);
    }

    /**
     * @return bool
     */
    public function belongsToGroup()
    {
        return !empty($this->groups);
    }
}
