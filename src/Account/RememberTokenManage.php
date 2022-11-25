<?php

/**
 * User: Maros Jasan
 * Date: 21.11.2019
 * Time: 11:06
 */

namespace Geodeticca\Iam\Account;

use Illuminate\Support\Str;

trait RememberTokenManage
{
    /**
     * @var string
     */
    public $remember_token;

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * @return $this
     */
    public function resetRememberToken(): self
    {
        $token = Str::random(60);

        $this->setRememberToken($token);

        return $this;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function checkRememberToken(string $token): bool
    {
        return hash_equals($this->remember_token, $token);
    }
}
