<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 13:56
 */

namespace Geodeticca\Iam\Client;

use Dense\Delivery\Result\Hydrator;
use Geodeticca\Iam\Identity\HasIdentity;
use Geodeticca\Iam\Remote\HasRemote;
use Geodeticca\Iam\Identity\IdentityContract;
use Geodeticca\Iam\Remote\RemoteContract;

abstract class ProxyClient
{
    use Hydrator, HasIdentity, HasRemote;

    /**
     * @return string
     */
    abstract protected function state() : string;

    /**
     * ProxyClient constructor.
     * @param \Geodeticca\Iam\Identity\IdentityContract $identity
     * @param \Geodeticca\Iam\Remote\RemoteContract $remote
     */
    public function __construct(IdentityContract $identity, RemoteContract $remote)
    {
        $this->setIdentity($identity);
        $this->setRemote($remote);
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
        ];

        // before every call on remote client, token is inserted into client's header
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

            $token = $this->identity->token();

            $this->remote->setAuthHeader($token);
        }

        return $this->remote->{$name}(...$arguments);
    }
}
