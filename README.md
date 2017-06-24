Laravel Manager
===============

Laravel Manager was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and provides some manager functionality for [Laravel 5](http://laravel.com). Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Manager/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).

![Laravel Manager](https://cloud.githubusercontent.com/assets/2829600/4432296/c122a676-468c-11e4-98c1-ba67fc40b641.PNG)

<p align="center">
<a href="https://styleci.io/repos/19836262"><img src="https://styleci.io/repos/19836262/shield" alt="StyleCI Status"></img></a>
<a href="https://travis-ci.org/GrahamCampbell/Laravel-Manager"><img src="https://img.shields.io/travis/GrahamCampbell/Laravel-Manager/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Manager.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager"><img src="https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Manager.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Manager/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Manager.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

Laravel Manager requires [PHP](https://php.net) 5.5+. This particular version supports Laravel 5.1, 5.2, 5.3, or 5.4 only.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require graham-campbell/manager
```

Once installed, you can extend or implement the classes in this package to speed up writing Laravel packages further. There are no service providers to register.


## Configuration

Laravel Manager requires no configuration. Just follow the simple install instructions and go!


## Usage

##### ConnectorInterface

This interface defines one public method.

The `'connect'` method accepts one parameter which is an array of config.

This interface is not used by this package, but is used by [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem) and [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox).

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

For a simple example of a manager class implementing these methods, see my [DropboxManager](https://github.com/GrahamCampbell/Laravel-Dropbox/blob/master/src/DropboxManager.php) class from my [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox) package.

##### Further Information

You may see an example of implementation in [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem), [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox), [Laravel DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean), and [Laravel GitHub](https://github.com/GrahamCampbell/Laravel-GitHub).


## Security

If you discover a security vulnerability within this package, please send an e-mail to Graham Campbell at graham@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Laravel Manager is licensed under [The MIT License (MIT)](LICENSE).
