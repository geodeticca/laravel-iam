<?php
/**
 * User: Maros Jasan
 * Date: 7.7.2020
 * Time: 10:36
 */

namespace Geodeticca\Iam\Identity;

interface IdentityContract
{
    /**
     * @param array $credentials
     * @return object
     */
    public function login(array $credentials): object;
}
