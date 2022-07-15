<?php
/**
 * User: Maros Jasan
 * Date: 4/8/2020
 * Time: 7:41 PM
 */

namespace Geodeticca\Iam\Jwt;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

use GuzzleHttp\Exception\TransferException;

use Dense\Jwt\Auth\Sign;
use Dense\Jwt\Auth\Resolver;
use Dense\Informer\Mail\InformerTrait;

use Geodeticca\Iam\Identity\IdentityContract;
use Geodeticca\Iam\Account\Account;

class JwtProvider implements UserProvider
{
    use InformerTrait;

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
     * @param string $token
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveById($token)
    {
        try {
            $claims = $this->sign->decode($token);

            if (!empty($claims)) {
                $account = Account::createFromJwt((array)$claims->usr);

                return $account;
            }
        } catch (\Exception $e) {
            $this->sendException($e);
            $this->sendDebug(sprintf('Auth JWT token: %s', Resolver::resolveToken()));
        }

        return null;
    }

    /**
     * @return string
     */
    public function getJwtToken(): string
    {
        return $this->identity->token();
    }

    /**
     * @param string $token
     * @param string $rememberToken
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveByToken($token, $rememberToken)
    {
        try {
            $claims = $this->sign->decode($token);

            if (!empty($claims)) {
                $account = Account::createFromJwt((array)$claims->usr);

                if ($account->remember_token === $rememberToken) {
                    return $account;
                }
            }
        } catch (\Exception $e) {
            $this->sendException($e);
            $this->sendDebug(sprintf('Auth JWT token: %s', Resolver::resolveToken()));
        }

        return null;
    }

    /**
     * @param \Geodeticca\Iam\Account\Account $user
     * @param string $rememberToken
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $rememberToken)
    {
    }

    /**
     * @param array $credentials
     * @return \Geodeticca\Iam\Account\Account|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $login = null;

        try {
            $login = $this->identity->login($credentials);
        } catch (TransferException $e) {
            $this->sendException($e);
        } catch (\Exception $e) {
            $this->sendException($e);
        }

        if (!is_null($login)) {
            try {
                $claims = $this->sign->decode($login->token);

                if (!empty($claims)) {
                    return Account::createFromJwt((array)$claims->usr);
                }
            } catch (\Exception $e) {
                $this->sendException($e);
                $this->sendDebug(sprintf('Auth JWT token: %s', $login->token));
            }
        }

        return null;
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
