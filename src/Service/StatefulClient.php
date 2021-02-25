<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

use Dense\Jwt\Auth\Resolver as JwtResolver;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class Auth
 * @package Geodeticca\Iam\Service
 */
class StatefulClient extends Client
{
    /**
     * @return string
     * @throws \Exception
     */
    public function token()
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
    protected function rememberToken($token)
    {
        // save token to property
        // this also serves as caching mechanism
        $this->token = $token;

        // since statefull client is used, save token to cookie
        JwtResolver::publishAuthCookie($this->token);

        return $this;
    }

    /**
     * @param array $credentials
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(array $credentials)
    {
        $endpoint = 'auth/login';

        // since statefull client is used, user input one time credentials are used
        $response = $this->client->post($endpoint, [
            'form_params' => $credentials,
        ]);

        $result = $this->getResult($response);

        $this->rememberToken($result->token);

        return $result;
    }
}
