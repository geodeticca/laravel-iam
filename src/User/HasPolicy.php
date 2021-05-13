<?php
/**
 * User: Maros Jasan
 * Date: 6. 11. 2020
 * Time: 18:12
 */

namespace Geodeticca\Iam\User;

use Geodeticca\Iam\Group\Policy as GroupPolicy;

trait HasPolicy
{
    /**
     * @var array
     */
    public $policy = [];

    /**
     * @param string $policy
     * @return $this
     */
    public function addPolicy($policy)
    {
        $this->policy[] = $policy;

        return $this;
    }

    /**
     * @return array
     */
    public function getPolicy()
    {
        return array_unique($this->policy);
    }

    /**
     * @return bool
     */
    public function hasAdminPolicy()
    {
        return in_array(GroupPolicy::POLICY_ADMIN, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoAdminPolicy()
    {
        return !$this->hasAdminPolicy();
    }

    /**
     * @return bool
     */
    public function hasManagerPolicy()
    {
        return in_array(GroupPolicy::POLICY_MANAGER, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoManagerPolicy()
    {
        return !$this->hasManagerPolicy();
    }
}
