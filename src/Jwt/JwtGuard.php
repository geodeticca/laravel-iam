<?php

/**
 * User: Maros Jasan
 * Date: 4/3/2020
 * Time: 5:24 PM
 */

namespace Geodeticca\Iam\Jwt;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\GuardHelpers;

use Dense\Jwt\Auth\Resolver;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var string
     */
    protected $cookieKey = 'jwt_token';

    /**
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @return void
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $this->user = $this->provider->retrieveById($token);

            return $this->user;
        }
    }

    /**
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = Resolver::resolveTokenFromCookie();

        return $token;
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return $this->provider->validateCredentials($this->user(), $credentials);
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        // credentials validatiion is happening on the side of the IAM service
        // all that is needed is to chcek if user was properly received
        if (!is_null($user)) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    public function logout()
    {
        Resolver::removeAuthCookie();
    }
}
