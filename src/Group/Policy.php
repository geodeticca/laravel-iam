<?php

namespace Geodeticca\Iam\Group;

use Dense\Enum\Core\EnumAbstract;

class Policy extends EnumAbstract
{
    const POLICY_ADMIN = 'ADMIN';
    const POLICY_MANAGER = 'MANAGER';
    const POLICY_REGULAR = 'REGULAR';

    /**
     * @return array
     */
    public static function getEnums()
    {
        return [
            self::POLICY_ADMIN => __('enum.group_policy_admin'),
            self::POLICY_MANAGER => __('enum.group_policy_manager'),
            self::POLICY_REGULAR => __('enum.group_policy_regular'),
        ];
    }
}
