# Workerman Redis watcher for PHP-Casbin

[![Latest Stable Version](https://poser.pugx.org/casbin/workerman-redis-watcher/v/stable)](https://packagist.org/packages/casbin/workerman-redis-watcher)
[![Total Downloads](https://poser.pugx.org/casbin/workerman-redis-watcher/downloads)](https://packagist.org/packages/casbin/workerman-redis-watcher)
[![License](https://poser.pugx.org/casbin/workerman-redis-watcher/license)](https://packagist.org/packages/casbin/workerman-redis-watcher)

[Workerman Redis](https://github.com/walkor/redis) watcher for [PHP-Casbin](https://github.com/php-casbin/php-casbin), [Casbin](https://casbin.org/) is a powerful and efficient open-source access control library.

### Installation

Via [Composer](https://getcomposer.org/).

```
composer require casbin/workerman-redis-watcher
```

### Usage

```php

require dirname(__FILE__) . '/../vendor/autoload.php';

use Casbin\Enforcer;
use CasbinWatcher\WorkermanRedis\Watcher;
use Workerman\Worker;


$worker = new Worker();
$worker->count = 2;
$worker->onWorkerStart = function () {

    // Initialize the Watcher.
    $watcher = new Watcher([
        'host' => '127.0.0.1',
        'password' => '',
        'port' => 6379,
        'database' => 0,
    ]);

    // Initialize the Enforcer.
    $enforcer = new Enforcer("path/to/model.conf", "path/to/policy.csv");

    $enforcer->setWatcher($watcher);
    
    // Set callback, sets the callback function that the watcher will call,
    // When the policy in DB has been changed by other instances.
    // A classic callback is $enforcer->loadPolicy().
    $watcher->setUpdateCallback(function () use ($enforcer) {
        echo "Now should reload all policies." . PHP_EOL;
        $enforcer->loadPolicy();
    });
};

Worker::runAll();

```

### Getting Help

- [php-casbin](https://github.com/php-casbin/php-casbin)

### License

This project is licensed under the [Apache 2.0 license](LICENSE).
