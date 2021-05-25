<?php

namespace Geodeticca\Iam\Account;

use Dense\Enum\Core\EnumAbstract;

class Authority extends EnumAbstract
{
    const AUTHORITY_ADMIN = 'ADMIN';
    const AUTHORITY_MANAGER = 'MANAGER';
    const AUTHORITY_REGULAR = 'REGULAR';
    const AUTHORITY_SYSTEM = 'SYSTEM';

    /**
     * @return array
     */
    public static function getEnums()
    {
        return [
            self::AUTHORITY_ADMIN => __('iam::enum.account_authority_admin'),
            self::AUTHORITY_MANAGER => __('iam::enum.account_authority_manager'),
            self::AUTHORITY_REGULAR => __('iam::enum.account_authority_regular'),
            self::AUTHORITY_SYSTEM => __('iam::enum.account_authority_system'),
        ];
    }
}
