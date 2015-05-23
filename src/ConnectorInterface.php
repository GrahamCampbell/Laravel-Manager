<?php

/*
 * This file is part of Laravel Manager.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Manager;

/**
 * This is the connector interface.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
interface ConnectorInterface
{
    /**
     * Establish a connection.
     *
     * @param array $config
     *
     * @return object
     */
    public function connect(array $config);
}
