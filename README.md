Laravel Manager
===============


[![Build Status](https://img.shields.io/travis/GrahamCampbell/Laravel-Manager/master.svg?style=flat-square)](https://travis-ci.org/GrahamCampbell/Laravel-Manager)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager)
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/GrahamCampbell/Laravel-Manager.svg?style=flat-square)](https://github.com/GrahamCampbell/Laravel-Manager/releases)


### Looking for a laravel 5 compatable version?

Checkout the [master branch](https://github.com/GrahamCampbell/Laravel-Manager/tree/master), installable by requiring `"graham-campbell/manager": "~2.0"`.


## Introduction

Laravel Manager was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and provides some manager functionality for [Laravel 4.1/4.2](http://laravel.com). Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Manager/releases), [license](LICENSE.md), [api docs](http://docs.grahamjcampbell.co.uk), and [contribution guidelines](CONTRIBUTING.md).


## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.2+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Manager, simply require `"graham-campbell/manager": "~1.0"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Manager is installed, you can extend or implement the classes in this package to speed up writing Laravel packages further. There are no service providers to register.


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

The `'createConnection'` method will be called with the specific connection config as the first paramater. It must return a connection instance.

The `'getConfigName'` method must return the name of the connection config. This may be `'yourname\yourpackage'` for example.

You can also dynamically call methods on the default connection due to the use of `__call` so instead of writing `->connection()->methodName()`, you can just jump straight in with `->methodName()`.

For a simple example of a manager class implementing these methods, see my [DropboxManager](https://github.com/GrahamCampbell/Laravel-Dropbox/blob/master/src/DropboxManager.php) class from my [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox) package.

##### Further Information

Feel free to check out the [API Documentation](http://docs.grahamjcampbell.co.uk) for Laravel Manager.

You may see an example of implementation in [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem), [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox), [Laravel DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean), and [Laravel GitHub](https://github.com/GrahamCampbell/Laravel-GitHub).


## License

Apache License

Copyright 2014 Graham Campbell

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
