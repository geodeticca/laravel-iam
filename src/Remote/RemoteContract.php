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
     * @return object
     */
    public function get(string $endpoint, array $params = []): object;

    /**
     * @param string $endpoint
     * @param array $params
     * @return object
     */
    public function post(string $endpoint, array $params = []): object;

    /**
     * @param string $endpoint
     * @param array $params
     * @return object
     */
    public function put(string $endpoint, array $params = []): object;

    /**
     * @param string $endpoint
     * @return object
     */
    public function delete(string $endpoint): object;
}
