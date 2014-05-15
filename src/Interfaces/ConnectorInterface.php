<?php

/**
 * This file is part of Laravel Manager by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Manager\Interfaces;

/**
 * This is the connector interface.
 *
 * @package    Laravel-Manager
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Manager
 */
interface ConnectorInterface
{
    /**
     * Establish a connection.
     *
     * @param  array  $config
     * @return mixed
     */
    public function connect(array $config);
}
