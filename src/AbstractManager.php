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

use Illuminate\Config\Repository;

/**
 * This is the abstract manager class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md> Apache 2.0
 */
abstract class AbstractManager implements ManagerInterface
{
    /**
     * The config instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = array();

    /**
     * The custom connection resolvers.
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * Create a new manager manager instance.
     *
     * @param \Illuminate\Config\Repository $config
     *
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Get a connection instance.
     *
     * @param string $name
     *
     * @return object
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Reconnect to the given connection.
     *
     * @param string $name
     *
     * @return object
     */
    public function reconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $this->disconnect($name);

        return $this->connection($name);
    }

    /**
     * Disconnect from the given connection.
     *
     * @param string $name
     *
     * @return void
     */
    public function disconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        unset($this->connections[$name]);
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return mixed
     */
    abstract protected function createConnection(array $config);

    /**
     * Make the connection instance.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function makeConnection($name)
    {
        $config = $this->getConnectionConfig($name);

        if (isset($this->extensions[$name])) {
            return call_user_func($this->extensions[$name], $config);
        }

        if ($driver = array_get($config, 'driver')) {
            if (isset($this->extensions[$driver])) {
                return call_user_func($this->extensions[$driver], $config);
            }
        }

        return $this->createConnection($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    abstract protected function getConfigName();

    /**
     * Get the configuration for a connection.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getConnectionConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->config->get($this->getConfigName().'::connections');

        if (!is_array($config = array_get($connections, $name)) && !$config) {
            throw new \InvalidArgumentException("Connection [$name] not configured.");
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->config->get($this->getConfigName().'::default');
    }

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->config->set($this->getConfigName().'::default', $name);
    }

    /**
     * Register an extension connection resolver.
     *
     * @param string   $name
     * @param callable $resolver
     *
     * @return void
     */
    public function extend($name, $resolver)
    {
        $this->extensions[$name] = $resolver;
    }

    /**
     * Return all of the created connections.
     *
     * @return object[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Get the config instance.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->connection(), $method), $parameters);
    }
}
