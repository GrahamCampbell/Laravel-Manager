<?php

/*
 * This file is part of Laravel Manager.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Manager;

use GrahamCampbell\Manager\AbstractManager;
use GrahamCampbell\TestBenchCore\MockeryTrait;
use Illuminate\Contracts\Config\Repository;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * This is the abstract manager test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AbstractManagerTest extends TestCase
{
    use MockeryTrait;

    public function testConnectionName()
    {
        $config = ['driver' => 'manager'];

        $manager = $this->getConfigManager($config);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('example');

        $this->assertInstanceOf(ExampleClass::class, $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect('example');

        $this->assertInstanceOf(ExampleClass::class, $return);

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
            ->with('manager.default')->andReturn('example');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf(ExampleClass::class, $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect();

        $this->assertInstanceOf(ExampleClass::class, $return);

        $this->assertSame('example', $return->getName());

        $this->assertSame('manager', $return->getDriver());

        $this->assertArrayHasKey('example', $manager->getConnections());

        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        $manager->disconnect();

        $this->assertSame([], $manager->getConnections());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Connection [error] not configured.
     */
    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = ['driver' => 'error'];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['example' => $config]);

        $this->assertSame([], $manager->getConnections());

        $manager->connection('error');
    }

    public function testDefaultConnection()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        $this->assertSame('example', $manager->getDefaultConnection());

        $manager->getConfig()->shouldReceive('set')->once()
            ->with('manager.default', 'new');

        $manager->setDefaultConnection('new');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('new');

        $this->assertSame('new', $manager->getDefaultConnection());
    }

    public function testExtendName()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['foo' => ['driver' => 'hello']]);

        $manager->extend('foo', function (array $config) {
            return new FooClass($config['name'], $config['driver']);
        });

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('foo');

        $this->assertInstanceOf(FooClass::class, $return);

        $this->assertSame('foo', $return->getName());

        $this->assertSame('hello', $return->getDriver());

        $this->assertArrayHasKey('foo', $manager->getConnections());
    }

    public function testExtendDriver()
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['qwerty' => ['driver' => 'bar']]);

        $manager->extend('bar', function (array $config) {
            return new BarClass($config['name'], $config['driver']);
        });

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('qwerty');

        $this->assertInstanceOf(BarClass::class, $return);

        $this->assertSame('qwerty', $return->getName());

        $this->assertSame('bar', $return->getDriver());

        $this->assertArrayHasKey('qwerty', $manager->getConnections());
    }

    public function testCall()
    {
        $config = ['driver' => 'manager'];

        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['example' => $config]);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->getName();

        $this->assertSame('example', $return);

        $this->assertArrayHasKey('example', $manager->getConnections());
    }

    protected function getManager()
    {
        $repo = Mockery::mock(Repository::class);

        return new ExampleManager($repo);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('manager.connections')->andReturn(['example' => $config]);

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
        return 'manager';
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
