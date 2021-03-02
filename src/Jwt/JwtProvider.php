<?php
/**
 * User: Maros Jasan
 * Date: 4/8/2020
 * Time: 7:41 PM
 */

namespace Geodeticca\Iam\Jwt;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

use Dense\Jwt\Auth\Sign;

use Geodeticca\Iam\Identity\IdentityContract;
use Geodeticca\Iam\Account\Account;

class JwtProvider implements UserProvider
{
    /**
     * @var \Dense\Jwt\Auth\Sign
     */
    protected \Dense\Jwt\Auth\Sign $sign;

    /**
     * @var \Geodeticca\Iam\Identity\IdentityContract
     */
    protected \Geodeticca\Iam\Identity\IdentityContract $identity;

    /**
     * @param \Dense\Jwt\Auth\Sign $sign
     * @param \Geodeticca\Iam\Identity\IdentityContract $identity
     */
    public function __construct(Sign $sign, IdentityContract $identity)
    {
        $this->sign = $sign;
        $this->identity = $identity;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param string $identifier
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveById($identifier)
    {
        try {
            $claims = $this->sign->decode($identifier);

            return Account::createFromJwt((array)$claims->usr);
        } catch (\Exception $e) {
        }
    }

    /**
     * @return string
     */
    public function getJwtToken(): string
    {
        return $this->identity->token();
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveByToken($identifier, $token)
    {
        try {
            $claims = $this->sign->decode($identifier);

            $user = $claims->user;

            if ($user->remember_token === $token) {
                return $user;
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @param \Geodeticca\Iam\Account\Account $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * @param array $credentials
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $account = null;

        try {
            $payload = $this->identity->login($credentials);

            $claims = $this->sign->decode($payload->token);

            if (!empty($claims)) {
                $account = Account::createFromJwt((array)$claims->usr);
            }
        } catch (\Exception $e) {
        }

        return $account;
    }

    /**
     * @param \Geodeticca\Iam\Account\Account $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials = [])
    {
        return !is_null($user);
    }
}
