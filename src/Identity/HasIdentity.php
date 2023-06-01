<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 11:37
 */

namespace Geodeticca\Iam\Identity;

trait HasIdentity
{
    /**
     * @var \Geodeticca\Iam\Identity\IdentityContract
     */
    protected \Geodeticca\Iam\Identity\IdentityContract $identity;

    /**
     * @param \Geodeticca\Iam\Identity\IdentityContract $identity
     * @return $this
     */
    public function setIdentity(IdentityContract $identity): self
    {
        $this->identity = $identity;

        return $this;
    }
}
