<?php
/**
 * User: Maros Jasan
 * Date: 19.2.2017
 * Time: 18:48
 */

namespace Geodeticca\Iam\Identity;

use GuzzleHttp\Client as GuzzleClient;
use Dense\Jwt\Auth\Sign as JwtSign;
use Dense\Jwt\Auth\Resolver as JwtResolver;
use Dense\Delivery\Service\Service as DeliveryService;

use Geodeticca\Iam\Account\Account;

/**
 * Zabezpecenie prihlasenia pomocou internej webovej sluzby IAM
 *
 * Class Identity
 * @package Geodeticca\Iam\Identity
 */
abstract class Identity implements IdentityContract
{
    use DeliveryService;

    /**
     * @var \Dense\Jwt\Auth\Sign
     */
    protected \Dense\Jwt\Auth\Sign $sign;

    /**
     * @var string
     */
    protected $token;

    /**
     * Identity constructor.
     *
     * @param \GuzzleHttp\Client $guzzle
     * @param \Dense\Jwt\Auth\Sign $sign
     */
    public function __construct(GuzzleClient $guzzle, JwtSign $sign)
    {
        $this->guzzle = $guzzle;
        $this->sign = $sign;
    }

    /**
     * @param string $token
     * @return $this
     */
    abstract protected function rememberToken(string $token): self;

    /**
     * @return string
     */
    abstract public function token(): string;

    /**
     * @param array $credentials
     * @return object
     */
    abstract public function login(array $credentials): object;

    /**
     * @return array
     */
    public function getDefaultParams(): array
    {
        if (!$this->defaultParams) {
            $this->defaultParams = [
                'headers' => [
                    'Authorization' => JwtResolver::createAuthHeader($this->token()),
                ],
            ];
        }

        return $this->defaultParams;
    }

    /**
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticated(): object
    {
        $endpoint = 'auth/authenticated';

        $response = $this->guzzle->post($endpoint, $this->buildFormData());

        return $this->getJson($response);
    }

    /**
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function extend(): object
    {
        $endpoint = 'auth/extend';

        $response = $this->guzzle->post($endpoint, $this->buildFormData());

        return $this->getJson($response);
    }

    /**
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accountDetail(): object
    {
        $endpoint = 'account/detail';

        $response = $this->guzzle->get($endpoint, $this->buildQuery());

        return $this->getJson($response);
    }

    /**
     * @param \Geodeticca\Iam\Account\Account $account
     * @param string|null $password
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accountUpdate(Account $account, string $password = null): object
    {
        $endpoint = 'account/update';

        $accountData = array_merge($account->toArray(), [
            'password' => $password,
        ]);
        $response = $this->guzzle->post($endpoint, $this->buildFormData($accountData));

        return $this->getJson($response);
    }

    /**
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accountReset(): object
    {
        $endpoint = 'account/reset';

        $response = $this->guzzle->post($endpoint, $this->buildFormData());

        return $this->getJson($response);
    }
}
