<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class Auth
 * @package Geodeticca\Iam\Service
 */
class StatelessClient extends Client
{
    /**
     * @var array
     */
    protected $credentials = [];

    /**
     * @param array $credentials
     * @return $this
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function token()
    {
        if (!$this->token) {
            if ($this->hasLoginCredentials()) {
                $this->login();
            }
        }

        return $this->token;
    }

    /**
     * @return bool
     */
    protected function hasLoginCredentials()
    {
        return
            isset($this->credentials['login']) &&
            isset($this->credentials['password']) &&
            isset($this->credentials['app']);
    }

    /**
     * @param string $token
     * @return $this
     */
    protected function rememberToken($token)
    {
        // since stateless client is used, save token to property
        $this->token = $token;

        return $this;
    }

    /**
     * @param array $credentials
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(array $credentials = [])
    {
        $endpoint = 'auth/login';

        // since stateless client is used, configured credentials are used
        $response = $this->client->post($endpoint, [
            'form_params' => array_merge($this->credentials, $credentials),
        ]);

        $result = $this->getResult($response);

        $this->rememberToken($result->token);

        return $result;
    }
}
