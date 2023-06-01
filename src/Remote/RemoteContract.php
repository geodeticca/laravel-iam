<?php
/**
 * User: Maros Jasan
 * Date: 7.7.2020
 * Time: 10:36
 */

namespace Geodeticca\Iam\Remote;

interface RemoteContract
{
    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function get(string $endpoint, array $params = []);

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function post(string $endpoint, array $params = []);

    /**
     * @param string $endpoint
     * @param array $params
     * @return mixed
     */
    public function put(string $endpoint, array $params = []);

    /**
     * @param string $endpoint
     * @return mixed
     */
    public function delete(string $endpoint);
}
