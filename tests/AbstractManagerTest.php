<?php

/*
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

namespace GrahamCampbell\Tests\Manager;

use GrahamCampbell\Manager\AbstractManager;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the abstract manager test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md> Apache 2.0
 */
class AbstractManagerTest extends AbstractTestCase
{
    public function testConnectionName()
    {
        $config = ['driver' => 'manager'];

        $manager = $this->getConfigManager($config);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('example');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\ExampleClass', $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect('example');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\ExampleClass', $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $manager = $this->getManager();

        $manager->disconnect('example');

        $this->assertSame([], $manager->getConnections());
    }

    public function testConnectionNull()
    {
        $config = ['driver' => 'manager'];

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\ExampleClass', $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect();

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\ExampleClass', $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $manager->disconnect();

        $this->assertSame([], $manager->getConnections());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = ['driver' => 'error'];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(['example' => $config]);

        $this->assertSame([], $manager->getConnections());

        $manager->connection('error');
    }

    public function testDefaultConnection()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $this->assertSame('example', $manager->getDefaultConnection());

        $manager->getConfig()->shouldReceive('set')->once()
            ->with('graham-campbell/manager::default', 'new');

        $manager->setDefaultConnection('new');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('new');

        $this->assertSame('new', $manager->getDefaultConnection());
    }

    public function testExtendName()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(['foo' => ['driver' => 'hello']]);

        $manager->extend('foo', function (array $config) {
            return new FooClass($config['name'], $config['driver']);
        });

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('foo');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\FooClass', $return);

        $this->assertSame('foo', $return->getName());

        $this->assertSame('hello', $return->getDriver());

        $this->assertArrayHasKey('foo', $manager->getConnections());
    }

    public function testExtendDriver()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(['qwerty' => ['driver' => 'bar']]);

        $manager->extend('bar', function (array $config) {
            return new BarClass($config['name'], $config['driver']);
        });

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('qwerty');

        $this->assertInstanceOf('GrahamCampbell\Tests\Manager\BarClass', $return);

        $this->assertSame('qwerty', $return->getName());

        $this->assertSame('bar', $return->getDriver());

        $this->assertArrayHasKey('qwerty', $manager->getConnections());
    }

    public function testCall()
    {
        $config = ['driver' => 'manager'];

        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::default')->andReturn('example');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/manager::connections')->andReturn(['example' => $config]);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->getName();

        $this->assertSame('example', $return);

        $this->assertArrayHasKey('example', $manager->getConnections());
    }

    protected function getManager()
    {
        $repo = Mockery::mock('Illuminate\Config\Repository');

        return new ExampleManager($repo);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('graham-campbell/manager::connections')->andReturn(['example' => $config]);

        return $manager;
    }
}

class ExampleManager extends AbstractManager
{
    /**
     * Create the connection instance.
     *
     * @param array $config
     *
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
