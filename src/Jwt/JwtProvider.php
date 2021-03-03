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

use Geodeticca\Iam\Service\Client as IamClient;
use Geodeticca\Iam\Account\Account;

class JwtProvider implements UserProvider
{
    /**
     * @var \Dense\Jwt\Auth\Sign
     */
    protected $sign;

    /**
     * @var \Geodeticca\Iam\Service\Client
     */
    protected $iam;

    /**
     * @param \Dense\Jwt\Auth\Sign $sign
     * @param \Geodeticca\Iam\Service\Client $iam
     * @return void
     */
    public function __construct(Sign $sign, IamClient $iam)
    {
        $this->sign = $sign;
        $this->iam = $iam;
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

            $account = Account::createFromJwt((array)$claims->usr);

            return $account;
        } catch (\Exception $e) {
        }
    }

    /**
     * @return string
     */
    public function getJwtToken()
    {
        return $this->iam->token();
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
            $payload = $this->iam->login($credentials);

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
