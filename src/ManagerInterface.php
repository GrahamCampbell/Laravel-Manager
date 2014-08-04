<?php

/**
 * This file is part of Laravel Manager by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Manager;

/**
 * This is the manager interface.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md> Apache 2.0
 */
interface ManagerInterface
{
    /**
     * Get a connection instance.
     *
     * @param string $name
     *
     * @return object
     */
    public function connection($name = null);

    /**
     * Reconnect to the given connection.
     *
     * @param string $name
     *
     * @return object
     */
    public function reconnect($name = null);

    /**
     * Disconnect from the given connection.
     *
     * @param string $name
     *
     * @return void
     */
    public function disconnect($name = null);

    /**
     * Get the configuration for a connection.
     *
     * @param string $name
     *
     * @return array
     */
    public function getConnectionConfig($name);

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection();

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultConnection($name);

    /**
     * Register an extension connection resolver.
     *
     * @param string   $name
     * @param callable $resolver
     *
     * @return void
     */
    public function extend($name, $resolver);

    /**
     * Return all of the created connections.
     *
     * @return object[]
     */
    public function getConnections();
}
