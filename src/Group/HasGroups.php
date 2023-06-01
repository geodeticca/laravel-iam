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
    public array $groups = [];

    /**
     * @param int $groupId
     * @return $this
     */
    public function addGroup(int $groupId): self
    {
        $this->groups[] = $groupId;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return array_values(array_unique($this->groups));
    }

    /**
     * @param array $groups
     * @return $this
     */
    public function setGroups(array $groups): self
    {
        $this->groups = array_values(array_unique(array_map('intval', $groups)));

        return $this;
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public function hasGroup(int $groupId): bool
    {
        return in_array($groupId, $this->groups);
    }

    /**
     * @return bool
     */
    public function belongsToGroup(): bool
    {
        return !empty($this->groups);
    }
}
