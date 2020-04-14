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

use Dense\Jwt\Auth\Sign;
use Dense\Jwt\Auth\Resolver;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var \Dense\Jwt\Auth\Sign
     */
    protected $sign;

    /**
     * @var string
     */
    protected $cookieKey = 'jwt_token';

    /**
     * Create a new authentication guard.
     *
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Dense\Jwt\Auth\Sign $sign
     * @return void
     */
    public function __construct(UserProvider $provider, Sign $sign)
    {
        $this->provider = $provider;
        $this->sign = $sign;
    }

    /**
     * Get the currently authenticated user.
     *
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
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {

    }
}
