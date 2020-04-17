<?php

/**
 * User: Maros Jasan
 * Date: 21.11.2019
 * Time: 11:06
 */

namespace Geodeticca\Iam\Account;

use Illuminate\Support\Facades\Hash;

trait PasswordManage
{
    /**
     * @var string
     */
    public $password;

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAuthPassword($value)
    {
        $this->password = Hash::make($value);

        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}
