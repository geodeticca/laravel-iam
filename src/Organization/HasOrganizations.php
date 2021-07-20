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
    public array $organizations = [];

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
    public function addOrganization(int $organizationId): self
    {
        $this->organizations[] = $organizationId;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrganizations(): array
    {
        return array_values(array_unique($this->organizations));
    }

    /**
     * @param array $organizations
     * @return $this
     */
    public function setOrganizations(array $organizations): self
    {
        $this->organizations = array_values(array_unique(array_map('intval', $organizations)));

        return $this;
    }

    /**
     * @param int $organizationId
     * @return bool
     */
    public function hasOrganization(int $organizationId): bool
    {
        return in_array($organizationId, $this->organizations);
    }

    /**
     * @return bool
     */
    public function belongsToOrganization(): bool
    {
        return !empty($this->organizations);
    }
}
