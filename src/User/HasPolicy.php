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
    public array $policy = [];

    /**
     * @param string $policy
     * @return $this
     */
    public function addPolicy(string $policy): self
    {
        $this->policy[] = $policy;

        return $this;
    }

    /**
     * @return array
     */
    public function getPolicy(): array
    {
        return array_values(array_unique($this->policy));
    }

    /**
     * @param array $policy
     * @return $this
     */
    public function setPolicy(array $policy): self
    {
        $this->policy = array_values(array_unique($policy));

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdminPolicy(): bool
    {
        return in_array(GroupPolicy::POLICY_ADMIN, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoAdminPolicy(): bool
    {
        return !$this->hasAdminPolicy();
    }

    /**
     * @return bool
     */
    public function hasManagerPolicy(): bool
    {
        return in_array(GroupPolicy::POLICY_MANAGER, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoManagerPolicy(): bool
    {
        return !$this->hasManagerPolicy();
    }
}
