<?php

/*
 * This file is part of Laravel Manager.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Manager;

/**
 * This is the manager interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
