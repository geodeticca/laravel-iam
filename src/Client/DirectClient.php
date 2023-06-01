<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 13:56
 */

namespace Geodeticca\Iam\Client;

use Dense\Delivery\Result\Hydrator;
use Geodeticca\Iam\Identity\HasIdentity;
use Geodeticca\Iam\Identity\IdentityContract;

abstract class DirectClient
{
    use Hydrator, HasIdentity;

    /**
     * @return string
     */
    abstract protected function state() : string;

    /**
     * DirectClient constructor.
     * @param \Geodeticca\Iam\Identity\IdentityContract $identity
     */
    public function __construct(IdentityContract $identity)
    {
        $this->setIdentity($identity);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $allowedMetrhods = [
            'get',
            'post',
            'put',
            'delete',
            'authenticated',
            'extend',
            'accountDetail',
            'accountUpdate',
            'accountReset',
        ];

        if (in_array($name, $allowedMetrhods)) {
            switch($this->state()) {
                case 'stateful':
                    break;

                case 'stateless':
                    //if (!$this->identity->isLogged()) {
                    $this->identity->login();
                    //}

                    break;
            }
        }

        return $this->identity->{$name}(...$arguments);
    }
}
