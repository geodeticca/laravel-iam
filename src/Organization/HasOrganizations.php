<?php

/**
 * Organization: Maros Jasan
 * Date: 5/13/2020
 * Time: 3:34 PM
 */

namespace Geodeticca\Iam\Organization;

trait HasOrganizations
{
    /**
     * @var array
     */
    public $organizations = [];

    /**
     * @return int
     */
    public function getOrganization()
    {
        return reset($this->organizations);
    }

    /**
     * @param int $organizationId
     * @return $this
     */
    public function addOrganization($organizationId)
    {
        $this->organizations[] = (int)$organizationId;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrganizations()
    {
        return array_unique($this->organizations);
    }

    /**
     * @param array $organizations
     * @return $this
     */
    public function setOrganizations(array $organizations)
    {
        $this->organizations = [];
        foreach ($organizations as $organizationId) {
            $this->addOrganization($organizationId);
        }

        return $this;
    }

    /**
     * @param int $organizationId
     * @return bool
     */
    public function hasOrganization($organizationId)
    {
        return in_array($organizationId, $this->organizations);
    }

    /**
     * @return bool
     */
    public function belongsToOrganization()
    {
        return !empty($this->organizations);
    }
}
