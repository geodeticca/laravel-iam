<?php

namespace Geodeticca\Iam\Account;

use Dense\Enum\Core\EnumAbstract;

class AccountAuthority extends EnumAbstract
{
    const AUTHORITY_ADMIN = 'ADMIN';
    const AUTHORITY_REGULAR = 'REGULAR';

    /**
     * @return array
     */
    public static function getEnums()
    {
        return [
            self::AUTHORITY_ADMIN => __('iam::enum.account_authority_admin'),
            self::AUTHORITY_REGULAR => __('iam::enum.account_authority_regular'),
        ];
    }
}
