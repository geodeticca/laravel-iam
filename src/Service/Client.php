<?php
/**
 * User: maros jasan
 * Date: 14.8.2017
 * Time: 11:17
 */

namespace Geodeticca\Iam\Service;

use GuzzleHttp\Client as GuzzleClient;
use Dense\Jwt\Auth\Sign as JwtSign;
use Dense\Jwt\Auth\Resolver as JwtResolver;

use Geodeticca\Iam\Account\Account;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class Auth
 * @package Geodeticca\Iam\Service
 */
abstract class Client implements ClientContract
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var \Dense\Jwt\Auth\Sign
     */
    protected $sign;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var  string
     */
    protected $token;

    /**
     * @var \Geodeticca\Iam\Account\Account
     */
    protected $user;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(GuzzleClient $client, JwtSign $sign)
    {
        $this->client = $client;
        $this->sign = $sign;
    }

    /**
     * @return string
     */
    abstract protected function token();

    /**
     * @param string $token
     * @return $this
     */
    abstract protected function rememberToken($token);

    /**
     * @return \Geodeticca\Iam\Account\Account
     */
    public function getUser()
    {
        if (!$this->user) {
            try {
                $claims = $this->sign->decode();
            } catch (\Exception $e) {
            }

            if ($claims) {
                // save user to property
                $this->user = (new Account())
                    ->hydrate((array)$claims->usr);
            }
        }

        return $this->user;
    }

    /**
     * @return array
     */
    protected function getDefaultParams()
    {
        if (empty($this->params)) {
            $token = $this->token();

            $this->params = [
                'headers' => [
                    'Authorization' => JwtResolver::createAuthHeader($token),
                ],
            ];
        }

        return $this->params;
    }

    /**
     * @param string $header
     * @param string $value
     * @return $this
     */
    protected function setDefaultHeader($header, $value)
    {
        $this->params['headers'][$header] = $value;

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
