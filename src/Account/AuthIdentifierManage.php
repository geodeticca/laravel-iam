<?php
/**
 * User: Maros Jasan
 * Date: 4/14/2020
 * Time: 5:35 PM
 */

namespace Geodeticca\Iam\Account;

trait AuthIdentifierManage
{
    /**
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    /**
     * @return int
     */
    public function getAuthIdentifier(): int
    {
        $identifier = $this->getAuthIdentifierName();

        return $this->{$identifier};
    }
}
