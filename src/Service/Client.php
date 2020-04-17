<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

use GuzzleHttp\Client as GuzzleClient;
use Dense\Jwt\Auth\Resolver as JwtResolver;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class Auth
 * @package Geodeticca\Iam\Service
 */
class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param string $type
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $headers
     * @return array
     */
    protected function getDefaultParams(array $headers = [])
    {
        $token = JwtResolver::resolveTokenFromCookie();

        return array_merge([
            'headers' => [
                'Authorization' => JwtResolver::createAuthHeader($token),
            ],
        ], $headers);
    }

    /**
     * @param string $data
     * @return mixed
     */
    protected function getResult($data)
    {
        $json = (string)$data->getBody();

        $result = json_decode($json, false);

        return (object)$result;
    }

    /**
     * @param string $data
     */
    public function debug($data)
    {
        $response = (string)$data->getBody();

        echo $response;
        exit();
    }

    /**
     * @param array $credentials
     * @return mixed
     */
    public function login(array $credentials)
    {
        $endpoint = 'auth/login';

        $response = $this->client->post($endpoint, [
            'form_params' => $credentials,
        ]);

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function authenticated()
    {
        $endpoint = 'auth/authenticated';

        $response = $this->client->post($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function extend()
    {
        $endpoint = 'auth/extend';

        $response = $this->client->post($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function account($account)
    {
        $endpoint = 'account';

        $response = $this->client->post($endpoint, $this->getDefaultParams([
            'form_params' => $account->toArray(),
        ]));

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function reset()
    {
        $endpoint = 'account/reset';

        $response = $this->client->post($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function apps()
    {
        $endpoint = 'app';

        $response = $this->client->get($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function users()
    {
        $endpoint = 'user';

        $response = $this->client->get($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function organizations()
    {
        $endpoint = 'organization';

        $response = $this->client->get($endpoint, $this->getDefaultParams());

        return $this->getResult($response);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }
}
