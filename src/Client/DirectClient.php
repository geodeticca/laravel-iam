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

class DirectClient
{
    use Hydrator, HasIdentity;

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
        return $this->identity->{$name}(...$arguments);
    }
}
