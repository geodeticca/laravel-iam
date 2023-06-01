<?php
/**
 * User: Maros Jasan
 * Date: 6. 11. 2020
 * Time: 18:17
 */

namespace Geodeticca\Iam\User;

interface PolicyManagement
{
    /**
     * @param string $policy
     * @return $this
     */
    public function addPolicy(string $policy): self;

    /**
     * @return array
     */
    public function getPolicy(): array;
}
