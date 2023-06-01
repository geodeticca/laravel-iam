<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Identity;

use Dense\Jwt\Auth\Resolver as JwtResolver;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class StatefulIdentity
 * @package Geodeticca\Iam\Identity
 */
class StatefulIdentity extends Identity
{
    /**
     * @return string
     */
    public function token(): string
    {
        if (!$this->token) {
            $this->token = JwtResolver::resolveTokenFromCookie();
        }

        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    protected function rememberToken(string $token): self
    {
        // save token to property
        // this also serves as caching mechanism
        $this->token = $token;

        // since stateful identity is used, save token to cookie
        JwtResolver::saveAuthCookie($this->token);

        return $this;
    }

    /**
     * @param array $credentials
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(array $credentials): object
    {
        $endpoint = 'auth/login';

        // since stateful identity is used, user input one time credentials are used
        // send request without any default params, only sends credentials as form-data in request body
        $result = $this->post($endpoint, $credentials);

        $this->rememberToken($result->token);

        return $result;
    }
}
