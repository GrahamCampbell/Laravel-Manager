<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Manager.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Manager;

use GrahamCampbell\Manager\AbstractManager;
use GrahamCampbell\TestBenchCore\MockeryTrait;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This is the abstract manager test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AbstractManagerTest extends TestCase
{
    use MockeryTrait;

    public function testConnectionName(): void
    {
        $config = ['driver' => 'manager'];

        $manager = self::getConfigManager($config);

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection('example');

        self::assertInstanceOf(ExampleClass::class, $return);

        self::assertSame('example', $return->getName());

        self::assertSame('manager', $return->getDriver());

        self::assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect('example');

        self::assertInstanceOf(ExampleClass::class, $return);

        self::assertSame('example', $return->getName());

        self::assertSame('manager', $return->getDriver());

        self::assertArrayHasKey('example', $manager->getConnections());

        $manager = self::getManager();

        $manager->disconnect('example');

        self::assertSame([], $manager->getConnections());
    }

    public function testConnectionNull(): void
    {
        $config = ['driver' => 'manager'];

        $manager = self::getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('manager.default')->andReturn('example');

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection();

        self::assertInstanceOf(ExampleClass::class, $return);

        self::assertSame('example', $return->getName());

        self::assertSame('manager', $return->getDriver());

        self::assertArrayHasKey('example', $manager->getConnections());

        $return = $manager->reconnect();

        self::assertInstanceOf(ExampleClass::class, $return);

        self::assertSame('example', $return->getName());

        self::assertSame('manager', $return->getDriver());

        self::assertArrayHasKey('example', $manager->getConnections());

        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        $manager->disconnect();

        self::assertSame([], $manager->getConnections());
    }

    public function testConnectionError(): void
    {
        $manager = self::getManager();

        $config = ['driver' => 'error'];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['example' => $config]);

        self::assertSame([], $manager->getConnections());

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Connection [error] not configured.');

        $manager->connection('error');
    }

    public function testDefaultConnection(): void
    {
        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        self::assertSame('example', $manager->getDefaultConnection());

        $manager->getConfig()->shouldReceive('set')->once()
            ->with('manager.default', 'new');

        $manager->setDefaultConnection('new');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('new');

        self::assertSame('new', $manager->getDefaultConnection());
    }

    public function testExtendName(): void
    {
        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['foo' => ['driver' => 'hello']]);

        $manager->extend('foo', function (array $config): FooClass {
            return new FooClass($config['name'], $config['driver']);
        });

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection('foo');

        self::assertInstanceOf(FooClass::class, $return);

        self::assertSame('foo', $return->getName());

        self::assertSame('hello', $return->getDriver());

        self::assertArrayHasKey('foo', $manager->getConnections());
    }

    public function testExtendDriver(): void
    {
        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['qwerty' => ['driver' => 'bar']]);

        $manager->extend('bar', function (array $config): BarClass {
            return new BarClass($config['name'], $config['driver']);
        });

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection('qwerty');

        self::assertInstanceOf(BarClass::class, $return);

        self::assertSame('qwerty', $return->getName());

        self::assertSame('bar', $return->getDriver());

        self::assertArrayHasKey('qwerty', $manager->getConnections());
    }

    public function testExtendDriverCallable(): void
    {
        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['qwerty' => ['driver' => 'bar']]);

        $manager->extend('bar', [BarFactory::class, 'create']);

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection('qwerty');

        self::assertInstanceOf(BarClass::class, $return);

        self::assertSame('qwerty', $return->getName());

        self::assertSame('bar', $return->getDriver());

        self::assertArrayHasKey('qwerty', $manager->getConnections());
    }

    public function testCall(): void
    {
        $config = ['driver' => 'manager'];

        $manager = self::getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.default')->andReturn('example');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('manager.connections')->andReturn(['example' => $config]);

        self::assertSame([], $manager->getConnections());

        $return = $manager->getName();

        self::assertSame('example', $return);

        self::assertArrayHasKey('example', $manager->getConnections());
    }

    private static function getManager(): ExampleManager
    {
        $repo = Mockery::mock(Repository::class);

        return new ExampleManager($repo);
    }

    private static function getConfigManager(array $config): ExampleManager
    {
        $manager = self::getManager();

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
    protected function createConnection(array $config): ExampleClass
    {
        return new ExampleClass($config['name'], $config['driver']);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName(): string
    {
        return 'manager';
    }
}

abstract class AbstractClass
{
    private string $name;
    private string  $driver;

    public function __construct(string $name, string $driver)
    {
        $this->name = $name;
        $this->driver = $driver;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDriver(): string
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

class BarFactory
{
    public static function create(array $config): BarClass
    {
        return new BarClass($config['name'], $config['driver']);
    }
}
