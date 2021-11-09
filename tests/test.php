<?php

require dirname(__FILE__) . '/../vendor/autoload.php';

use Casbin\Enforcer;
use CasbinWatcher\WorkermanRedis\Watcher;
use Workerman\Worker;


$worker = new Worker();
$worker->count = 2;
$worker->onWorkerStart = function () {
    $watcher = new Watcher([
        'host' => getenv('REDIS_HOST') ? getenv('REDIS_HOST') : '127.0.0.1',
        'password' => getenv('REDIS_PASSWORD') ? getenv('REDIS_PASSWORD') : '',
        'port' => getenv('REDIS_PORT') ? getenv('REDIS_PORT') : 6379,
        'database' => getenv('REDIS_DB') ? getenv('REDIS_DB') : 0,
    ]);

    $watcher->setUpdateCallback(function () {
        echo "Now should reload all policies." . PHP_EOL;
    });

    $enforcer = new Enforcer("path/to/model.conf", "path/to/policy.csv");
    $enforcer->setWatcher($watcher);

};

Worker::runAll();
