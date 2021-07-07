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
    public string $password;

    /**
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAuthPassword(string $value): self
    {
        $this->password = Hash::make($value);

        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
