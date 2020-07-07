<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

use GuzzleHttp\Client as GuzzleClient;

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
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param array $credentials
     * @return void
     */
    public function __construct(GuzzleClient $client, array $credentials)
    {
        parent::__construct($client);

        $this->credentials = $credentials;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function token()
    {
        /*
        if (!$this->token) {
            $this->login();
        }
        */

        return $this->token;
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
     * @return mixed
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
