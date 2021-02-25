<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 13:56
 */

namespace Geodeticca\Iam\Client;

use Geodeticca\Iam\Identity\HasIdentity;
use Geodeticca\Iam\Identity\IdentityContract;

class DirectClient
{
    use HasIdentity;

    /**
     * DirectClient constructor.
     * @param \Geodeticca\Iam\Identity\IdentityContract $identity
     */
    public function __construct(IdentityContract $identity)
    {
        $this->setIdentity($identity);
    }
}
