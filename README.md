[![Latest Stable Version](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/v/stable)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![Total Downloads](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/downloads)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![License](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/license)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![StyleCI](https://styleci.io/repos/126970369/shield?branch=master)](https://styleci.io/repos/126970369)
[![Build Status](https://travis-ci.org/freshbitsweb/laravel-log-enhancer.svg?branch=master)](https://travis-ci.org/freshbitsweb/laravel-log-enhancer)
[![Buy us a tree](https://img.shields.io/badge/Buy%20me%20a%20tree-%F0%9F%8C%B3-lightgreen?style=flat-square)](https://plant.treeware.earth/freshbitsweb/laravel-log-enhancer)

# Laravel Log Enhancer (Laravel 5.6+)
Laravel's logging system helps a lot for storing data as well as while troubleshooting some hidden bugs. The data related to the exception automatically gets logged whenever something goes wrong.

Sometimes, we need more than just *stack trace* to debug the issue easily. The things like **request URL**, **request input data**, **session data**, etc. help us hunt down the exact cause quickly. That's what this *plug-and-play* Laravel package does for you :)

## Requirements

* PHP 7.2.5+
* Laravel 5.6+

**Notes**:
- If you are still using PHP <7.2.5 and Laravel 6.x, you may use v1.1.0 of this package.
- If you are still using PHP 7.1 and Laravel 5.\*, you may use v1.0.* of this package.
- For Laravel 5.5, you may use [Slack Error Notifier](https://github.com/freshbitsweb/slack-error-notifier) package.

## Installation

1) Install the package by running this command in your terminal/cmd:
```
composer require freshbitsweb/laravel-log-enhancer
```

2) Add this package's LogEnhancer class to the tap option of your log channel in **config/logging.php**:
```
'production_stack' => [
    'driver' => 'stack',
    'tap' => [Freshbitsweb\LaravelLogEnhancer\LogEnhancer::class],
    'channels' => ['daily', 'slack'],
],
```

Optionally, you can import config file by running this command in your terminal/cmd:
```
php artisan vendor:publish --tag=laravel-log-enhancer-config
```

It has following configuration settings:
* (bool) log_request_details => Set to *true* if you wish to log request data. [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/WebProcessor.php)

* (bool) log_input_data => Set to *true* if you wish to log user input data

* (bool) log_request_headers => Set to *true* if you wish to log request headers

* (bool) log_session_data => Set to *true* if you wish to log session data

* (bool) log_memory_usage => Set to *true* if you wish to log memory usage [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/MemoryUsageProcessor.php)

* (bool) log_git_data => Set to *true* if you wish to log git branch and commit details [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/GitProcessor.php)

* (array) ignore_input_fields => If input data is being sent, you can specify the inputs from the user that should not be logged. for example, password,cc number, etc.

## Lumen compatibility

We do not use Lumen in any of our projects. And this package is not tested with Lumen framework.
If you are [willing](https://github.com/freshbitsweb/laravel-log-enhancer/issues/15) [to](https://github.com/freshbitsweb/laravel-log-enhancer/issues/16) use this package with Lumen, please help us by testing and making a PR to make it work. Thanks.

## Authors

* [**Gaurav Makhecha**](https://github.com/gauravmak) - *Initial work*

See also the list of [contributors](https://github.com/freshbitsweb/laravel-log-enhancer/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Treeware

You're free to use this package, but if it makes it to your production environment I would highly appreciate you buying the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to <a href="https://www.bbc.co.uk/news/science-environment-48870920">plant trees</a>. If you contribute to our forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at for our forest here [offset.earth/treeware](https://plant.treeware.earth/freshbitsweb/laravel-log-enhancer)

Read more about Treeware at [treeware.earth](http://treeware.earth)

## Special Thanks to

* [Laravel](https://laravel.com) Community
