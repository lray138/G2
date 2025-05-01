<?php 

// for quick tests

require dirname(__DIR__) . '/vendor/autoload.php';

use lray138\G2\Task;
use GuzzleHttp\Promise\Promise;



$task = Task::get('https://jsonplaceholder.typicode.com/posts/1')
        ->decodeJson()
        ->map(fn(Arr $arr) => $arr->prop('id')->get());

 $v = $task->run();

dump($v);

