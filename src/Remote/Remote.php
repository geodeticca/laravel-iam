<?php
/**
 * User: Maros Jasan
 * Date: 19.2.2017
 * Time: 18:48
 */

namespace Geodeticca\Iam\Remote;

use GuzzleHttp\Client as GuzzleClient;
use Dense\Jwt\Auth\Resolver as JwtResolver;
use Dense\Delivery\Service\Service as DeliveryService;

/**
 * Zabezpecenie komunikacie so vzdialenou sluzbou
 *
 * Class Remote
 * @package Geodeticca\Iam\Remote
 */
class Remote implements RemoteContract
{
    use DeliveryService;

    /**
     * Remote constructor.
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setAuthHeader(string $token): self
    {
        $this->mergeDefaultParams([
            'headers' => [
                'Authorization' => JwtResolver::createAuthString($token),
            ],
        ]);

        return $this;
    }
}
