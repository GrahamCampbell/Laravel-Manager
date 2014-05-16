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

namespace GrahamCampbell\Tests\Manager\Managers;

use Mockery;
use GrahamCampbell\Manager\Managers\AbstractManager;
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

/**
 * This is the abstract manager test class.
 *
 * @package    Laravel-Manager
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Manager
 */
class AbstractManagerTest extends AbstractTestCase
{
    public function testConnectionName()
    {
        $config = array('driver' => 'manager');

        $manager = $this->getConfigManager($config);

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection('example');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\ExampleClass', $return);

        $this->assertEquals($return->getName(), 'example');

        $this->assertEquals($return->getDriver(), 'manager');

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect('example');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\ExampleClass', $return);

        $this->assertEquals($return->getName(), 'example');

        $this->assertEquals($return->getDriver(), 'manager');

        $this->assertArrayHasKey('example', $manager->getConnections());

        $manager = $this->getExampleManager();

        $manager->disconnect('example');

        $this->assertEquals($manager->getConnections(), array());
    }

    public function testConnectionNull()
    {
        $config = array('driver' => 'manager');

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection();

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\ExampleClass', $return);

        $this->assertEquals($return->getName(), 'example');

        $this->assertEquals($return->getDriver(), 'manager');

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect();

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\ExampleClass', $return);

        $this->assertEquals($return->getName(), 'example');

        $this->assertEquals($return->getDriver(), 'manager');

        $this->assertArrayHasKey('example', $manager->getConnections());

        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $manager->disconnect();

        $this->assertEquals($manager->getConnections(), array());
    }

    public function testConnectionError()
    {
        $manager = $this->getExampleManager();

        $config = array('driver' => 'error');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(array('example' => $config));

        $this->assertEquals($manager->getConnections(), array());

        $return = null;

        try {
            $manager->connection('error');
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testGetDefaultConnection()
    {
        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $return = $manager->getDefaultConnection();

        $this->assertEquals($return, 'example');
    }

    public function testSetDefaultConnection()
    {
        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('set')->once()
            ->with('graham-campbell/manager::default', 'new');

        $manager->setDefaultConnection('new');
    }

    public function testExtendName()
    {
        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(array('foo' => array('driver' => 'hello')));

        $manager->extend('foo', function (array $config) {
            return new FooClass($config['name'], $config['driver']);
        });

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection('foo');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\FooClass', $return);

        $this->assertEquals($return->getName(), 'foo');

        $this->assertEquals($return->getDriver(), 'hello');

        $this->assertArrayHasKey('foo', $manager->getConnections());
    }

    public function testExtendDriver()
    {
        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(array('qwerty' => array('driver' => 'bar')));

        $manager->extend('bar', function (array $config) {
            return new BarClass($config['name'], $config['driver']);
        });

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection('qwerty');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\Managers\BarClass', $return);

        $this->assertEquals($return->getName(), 'qwerty');

        $this->assertEquals($return->getDriver(), 'bar');

        $this->assertArrayHasKey('qwerty', $manager->getConnections());
    }

    public function testCall()
    {
        $config = array('driver' => 'manager');

        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(array('example' => $config));

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->getName();

        $this->assertEquals($return, 'example');

        $this->assertArrayHasKey('example', $manager->getConnections());
    }

    protected function getExampleManager()
    {
        $repo = Mockery::mock('Illuminate\Config\Repository');

        return new ExampleManager($repo);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getExampleManager();

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('graham-campbell/manager::connections')->andReturn(array('example' => $config));

        return $manager;
    }
}

class ExampleManager extends AbstractManager
{
    /**
     * Create the connection instance.
     *
     * @param  array  $config
     * @return string
     */
    protected function createConnection(array $config)
    {
        return new ExampleClass($config['name'], $config['driver']);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'graham-campbell/manager';
    }
}

abstract class AbstractClass
{
    protected $name;
    protected $driver;

    public function __construct($name, $driver)
    {
        $this->name = $name;
        $this->driver = $driver;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDriver()
    {
        return $this->driver;
    }
}

class ExampleClass extends AbstractClass
{
    //
}


class FooClass extends AbstractClass
{
    //
}

class BarClass extends AbstractClass
{
    //
}
