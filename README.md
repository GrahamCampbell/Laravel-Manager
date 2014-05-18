Laravel Manager
=================


[![Build Status](https://img.shields.io/travis/GrahamCampbell/Laravel-Manager/master.svg)](https://travis-ci.org/GrahamCampbell/Laravel-Manager)
[![Coverage Status](https://img.shields.io/coveralls/GrahamCampbell/Laravel-Manager/master.svg)](https://coveralls.io/r/GrahamCampbell/Laravel-Manager)
[![Software License](https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg)](https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/GrahamCampbell/Laravel-Manager.svg)](https://github.com/GrahamCampbell/Laravel-Manager/releases)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/813427dd-796f-447f-b0fe-40889567b475/mini.png)](https://insight.sensiolabs.com/projects/813427dd-796f-447f-b0fe-40889567b475)


## What Is Laravel Manager?

Laravel Manager provides some manager functionality for [Laravel 4.1](http://laravel.com).

* Laravel Manager was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell).
* Laravel Manager uses [Travis CI](https://travis-ci.org/GrahamCampbell/Laravel-Manager) with [Coveralls](https://coveralls.io/r/GrahamCampbell/Laravel-Manager) to check everything is working.
* Laravel Manager uses [Scrutinizer CI](https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Manager) and [SensioLabsInsight](https://insight.sensiolabs.com/projects/813427dd-796f-447f-b0fe-40889567b475) to run additional checks.
* Laravel Manager uses [Composer](https://getcomposer.org) to load and manage dependencies.
* Laravel Manager provides a [change log](https://github.com/GrahamCampbell/Laravel-Manager/blob/master/CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Manager/releases), and [api docs](http://grahamcampbell.github.io/Laravel-Manager).
* Laravel Manager is licensed under the Apache License, available [here](https://github.com/GrahamCampbell/Laravel-Manager/blob/master/LICENSE.md).


## System Requirements

* PHP 5.4.7+ or HHVM 3.0+.
* You will need [Laravel 4.1](http://laravel.com) because this package is designed for it.
* You will need [Composer](https://getcomposer.org) installed to load the dependencies of Laravel Manager.


## Installation

Please check the system requirements before installing Laravel Manager.

To get the latest version of Laravel Manager, simply require `"graham-campbell/manager": "0.1.*@alpha"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Manager is installed, you can extend or implement the classes in this package to speed up writing Laravel packages further. There are no service providers to register.


## Configuration

Laravel Manager requires no configuration. Just follow the simple install instructions and go!


## Usage

**Interfaces\ConnectorInterface**

This interface defines one public method.

The `'connect'` method accepts one paramater which is an array of config.

This interface is not used by this package, but is used by [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem) and [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox).

**Interfaces\ManagerInterface**

This interface defines the public methods a manager class must implement.

The `'connection'` method accepts one optional parameter (the connection name), and will return a connection instance and will reuse a previous connection from the pool if possible.

The `'reconnect'` method accepts one optional parameter (the connection name), and will return a connection instance after forcing a re-connect.

The `'disconnect'` method accepts one optional parameter (the connection name), and will return nothing after removing the connection from the pool.

The `'getConnectionConfig'` method has one required parameter (the connection name), and will return the config for the specified connection.

The `'getDefaultConnection'` method will return the default connection as specified in the config.

The `'setDefaultConnection'` method has one required parameter (the connection name), and will return nothing after setting the default connection.

The `'extend'` method has two required parameter. The first is the name of a connection, or the name of a connection driver. The second parameter must be `callable`. The purpose of this method is to add custom connection creation methods on the fly. The second parameter must return a connection.

The `'getConnections'` method will return an array of all the connections currently in the pool.

**Managers\AbstractManager**

This abstract class implements the ManagerInterface. It has two abstract protected methods that must be implemented by extending classes.

The `'createConnection'` method will be called with the specific connection config as the first paramater. It must return a connection instance.

The `'getConfigName'` method must return the name of the connection config. This may be `'yourname\yourpackage'` for example.

For a simple example of a manager class implementing these methods, see my [DropboxManager](https://github.com/GrahamCampbell/Laravel-Dropbox/blob/master/src/Managers/DropboxManager.php) class from my [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox) package.

**Further Information**

Feel free to check out the [API Documentation](http://grahamcampbell.github.io/Laravel-Manager
) for Laravel Manager.

You may see an example of implementation in [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem) and [Laravel Dropbox](https://github.com/GrahamCampbell/Laravel-Dropbox).


## Updating Your Fork

Before submitting a pull request, you should ensure that your fork is up to date.

You may fork Laravel Manager:

    git remote add upstream git://github.com/GrahamCampbell/Laravel-Manager.git

The first command is only necessary the first time. If you have issues merging, you will need to get a merge tool such as [P4Merge](http://perforce.com/product/components/perforce_visual_merge_and_diff_tools).

You can then update the branch:

    git pull --rebase upstream master
    git push --force origin <branch_name>

Once it is set up, run `git mergetool`. Once all conflicts are fixed, run `git rebase --continue`, and `git push --force origin <branch_name>`.


## Pull Requests

Please review these guidelines before submitting any pull requests.

* When submitting bug fixes, check if a maintenance branch exists for an older series, then pull against that older branch if the bug is present in it.
* Before sending a pull request for a new feature, you should first create an issue with [Proposal] in the title.
* Please follow the [PSR-2 Coding Style](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and [PHP-FIG Naming Conventions](https://github.com/php-fig/fig-standards/blob/master/bylaws/002-psr-naming-conventions.md).


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
