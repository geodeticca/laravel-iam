<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 13:56
 */

namespace Geodeticca\Iam\Client;

use Geodeticca\Iam\Identity\HasIdentity;
use Geodeticca\Iam\Remote\HasRemote;
use Geodeticca\Iam\Identity\IdentityContract;
use Geodeticca\Iam\Remote\RemoteContract;

class ProxyClient
{
    use HasIdentity, HasRemote;

    /**
     * RemoteClient constructor.
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
        // before every call on remote client, token is inserted into client's header
        if (in_array($name, ['get', 'post', 'put', 'delete'])) {
            $this->remote->setAuthHeader($this->identity->token());
        }

        return $this->remote->{$name}(...$arguments);
    }
}
