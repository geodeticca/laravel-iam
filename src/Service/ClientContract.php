<?php
/**
 * User: Maros Jasan
 * Date: 7.7.2020
 * Time: 10:36
 */

namespace Geodeticca\Iam\Service;

interface ClientContract
{
    /**
     * @param array $credentials
     * @return mixed
     */
    public function login(array $credentials);

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function get($endpoint, array $params = []);

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function post($endpoint, array $params = []);

    /**
     * @param string $endpoint
     * @return mixed
     */
    public function put($endpoint);

    /**
     * @param string $endpoint
     * @return mixed
     */
    public function delete($endpoint);
}
