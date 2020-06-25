<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

use GuzzleHttp\Client as GuzzleClient;
use Dense\Jwt\Auth\Resolver as JwtResolver;

use Geodeticca\Iam\Account\Account;

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
     * @var array
     */
    protected $params = [];

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
     * @return array
     */
    protected function getDefaultParams()
    {
        if (empty($this->params)) {
            $token = JwtResolver::resolveTokenFromCookie();

            $this->params = [
                'headers' => [
                    'Authorization' => JwtResolver::createAuthHeader($token),
                ],
            ];
        }

        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    protected function setDefaultParams(array $params)
    {
        $this->params = array_merge($this->getDefaultParams(), $params);

        return $this;
    }

    /**
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function setDefaultHeader($header, $value)
    {
        $this->setDefaultParams([
            'headers' => [
                $header => $value,
            ],
        ]);

        return $this;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function params(array $params = [])
    {
        $defaultParams = $this->getDefaultParams();

        return array_merge($defaultParams, $params);
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

        $response = $this->post($endpoint);

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function extend()
    {
        $endpoint = 'auth/extend';

        $response = $this->post($endpoint);

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function accountDetail()
    {
        $endpoint = 'account/detail';

        $response = $this->get($endpoint);

        return $this->getResult($response);
    }

    /**
     * @param \Geodeticca\Iam\Account\Account $account
     * @param string|null $password
     * @return mixed
     */
    public function accountUpdate(Account $account, $password = null)
    {
        $endpoint = 'account/update';

        $response = $this->post($endpoint, array_merge($account->toArray(), [
            'password' => $password,
        ]));

        return $this->getResult($response);
    }

    /**
     * @return mixed
     */
    public function accountReset()
    {
        $endpoint = 'account/reset';

        $response = $this->post($endpoint);

        return $this->getResult($response);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function get($endpoint, array $params = [])
    {
        $response = $this->client->get($endpoint, $this->params([
            'query' => $params,
        ]));

        return $this->getResult($response);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function post($endpoint, array $params = [])
    {
        $response = $this->client->post($endpoint, $this->params([
            'form_params' => $params,
        ]));

        return $this->getResult($response);
    }

    /**
     * @param string $endpoint
     * @return mixed
     */
    public function put($endpoint)
    {
        $response = $this->client->put($endpoint, $this->params());

        return $this->getResult($response);
    }

    /**
     * @param string $endpoint
     * @return mixed
     */
    public function delete($endpoint)
    {
        $response = $this->client->delete($endpoint, $this->params());

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
