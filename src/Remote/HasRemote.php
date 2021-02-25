<?php
/**
 * User: Maros Jasan
 * Date: 22. 2. 2021
 * Time: 11:37
 */

namespace Geodeticca\Iam\Remote;

trait HasRemote
{
    /**
     * @var \Geodeticca\Iam\Remote\RemoteContract
     */
    protected \Geodeticca\Iam\Remote\RemoteContract $remote;

    /**
     * @param \Geodeticca\Iam\Remote\RemoteContract $remote
     * @return $this
     */
    public function setRemote(RemoteContract $remote): self
    {
        $this->remote = $remote;

        return $this;
    }
}
