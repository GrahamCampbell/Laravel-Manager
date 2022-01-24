Laravel Manager
===============

Laravel Manager was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and provides some manager functionality for [Laravel](https://laravel.com). Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Manager/releases), [security policy](https://github.com/GrahamCampbell/Laravel-Manager/security/policy), [license](LICENSE), [code of conduct](.github/CODE_OF_CONDUCT.md), and [contribution guidelines](.github/CONTRIBUTING.md).

![Banner](https://user-images.githubusercontent.com/2829600/71477504-680d0f80-27e2-11ea-9acd-befa0b3e3a8f.png)

<p align="center">
<a href="https://github.com/GrahamCampbell/Laravel-Manager/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/workflow/status/GrahamCampbell/Laravel-Manager/Tests?label=Tests&style=flat-square" alt="Build Status"></img></a>
<a href="https://github.styleci.io/repos/19836262"><img src="https://github.styleci.io/repos/19836262/shield" alt="StyleCI Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/graham-campbell/manager"><img src="https://img.shields.io/packagist/dt/graham-campbell/manager?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Manager/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Manager?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

Laravel Manager requires [PHP](https://php.net) 7.1-8.1. This particular version supports Laravel 5.5-9.

| Manager | L5.1               | L5.2               | L5.3               | L5.4               | L5.5               | L5.6               | L5.7               | L5.8               | L6                 | L7                 | L8                 | L9                 |
|---------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| 2.5     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 3.0     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 4.7     | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require "graham-campbell/manager:^4.7"
```

Once installed, you can extend or implement the classes in this package to speed up writing Laravel packages further. There are no service providers to register.


## Configuration

Laravel Manager requires no configuration. Just follow the simple install instructions and go!


## Usage

##### ConnectorInterface

This interface defines one public method.

The `'connect'` method accepts one parameter which is an array of config.

This interface is not used by this package, but is used by [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem).

##### ManagerInterface

This interface defines the public methods a manager class must implement.

The `'connection'` method accepts one optional parameter (the connection name), and will return a connection instance and will reuse a previous connection from the pool if possible.

The `'reconnect'` method accepts one optional parameter (the connection name), and will return a connection instance after forcing a re-connect.

The `'disconnect'` method accepts one optional parameter (the connection name), and will return nothing after removing the connection from the pool.

The `'getConnectionConfig'` method has one required parameter (the connection name), and will return the config for the specified connection.

The `'getDefaultConnection'` method will return the default connection as specified in the config.

The `'setDefaultConnection'` method has one required parameter (the connection name), and will return nothing after setting the default connection.

The `'extend'` method has two required parameter. The first is the name of a connection, or the name of a connection driver. The second parameter must be `callable`. The purpose of this method is to add custom connection creation methods on the fly. The second parameter must return a connection.

The `'getConnections'` method will return an array of all the connections currently in the pool.

##### AbstractManager

This abstract class implements the `ManagerInterface`. It has two abstract protected methods that must be implemented by extending classes.

The `'createConnection'` method will be called with the specific connection config as the first parameter. It must return a connection instance.

The `'getConfigName'` method must return the name of the connection config. This may be `'yourname\yourpackage'` for example.

You can also dynamically call methods on the default connection due to the use of `__call` so instead of writing `->connection()->methodName()`, you can just jump straight in with `->methodName()`.

##### Further Information

You may see an example of implementation in [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem), [Laravel DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean), and [Laravel GitHub](https://github.com/GrahamCampbell/Laravel-GitHub).


## Security

If you discover a security vulnerability within this package, please send an email to security@tidelift.com. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/GrahamCampbell/Laravel-Manager/security/policy).


## License

Laravel Manager is licensed under [The MIT License (MIT)](LICENSE).


## For Enterprise

Available as part of the Tidelift Subscription

The maintainers of `graham-campbell/manager` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-graham-campbell-manager?utm_source=packagist-graham-campbell-manager&utm_medium=referral&utm_campaign=enterprise&utm_term=repo)
