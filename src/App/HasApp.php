<?php

/**
 * User: Maros Jasan
 * Date: 12.2.2020
 * Time: 9:41
 */

namespace Geodeticca\Iam\App;

trait HasApp
{
    /**
     * @var int
     */
    public int $app_id;

    /**
     * @return bool
     */
    public function belongsToApp(): bool
    {
        return !is_null($this->app_id);
    }
}
