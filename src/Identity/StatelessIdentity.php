<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Identity;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class StatelessIdentity
 * @package Geodeticca\Iam\Identity
 */
class StatelessIdentity extends Identity
{
    /**
     * @var array
     */
    protected array $credentials = [];

    /**
     * @param array $credentials
     * @return $this
     */
    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasCredentials(): bool
    {
        return
            isset($this->credentials['login']) &&
            isset($this->credentials['password']) &&
            isset($this->credentials['app']);
    }

    /**
     * @param array $credentials
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(array $credentials = []): object
    {
        $endpoint = 'auth/login';

        if ($this->hasCredentials()) {
            $credentials = array_merge($this->credentials, $credentials);
        }

        // since stateless identity is used, configured credentials are inserted into request
        // send request without any default params, only sends credentials as form-data in request body
        $result = $this->post($endpoint, $credentials);

        $this->rememberToken($result->token);

        return $result;
    }
}
