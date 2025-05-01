<?php 

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Promise\Promise;

use lray138\G2\Task;
use lray138\G2\Arr;

test('Task::get retrieves a post', function () {
    $task = Task::get('https://jsonplaceholder.typicode.com/posts/1')
        ->decodeJson()
        ->map(fn(Arr $arr) => $arr->prop('id')->extract());

    expect($task->run())->toBe(1);
});

// test('Task::post creates a new post (simulated)', function () {
//     $data = [
//         'title' => 'foo',
//         'body' => 'bar',
//         'userId' => 1
//     ];

//     $task = Task::post('https://jsonplaceholder.typicode.com/posts', $data)
//         ->decodeJson()
//         ->map(fn(Arr $arr) => $arr->Prop('title'));

//     expect($task->run())->toBe('foo');
// });

// test('Task::put updates a post (simulated)', function () {
//     $data = [
//         'id' => 1,
//         'title' => 'updated',
//         'body' => 'bar',
//         'userId' => 1
//     ];

//     $task = Task::request('PUT', 'https://jsonplaceholder.typicode.com/posts/1', [
//         'json' => $data
//     ])
//         ->decodeJson()
//         ->map(fn(Arr $arr) => $arr->Prop('title'));

//     expect($task->run())->toBe('updated');
// });

// test('Task::patch partially updates a post (simulated)', function () {
//     $task = Task::request('PATCH', 'https://jsonplaceholder.typicode.com/posts/1', [
//         'json' => ['title' => 'patched']
//     ])
//         ->decodeJson()
//         ->map(fn(Arr $arr) => $arr->Prop('title'));

//     expect($task->run())->toBe('patched');
// });

// test('Task::delete removes a post (simulated)', function () {
//     $task = Task::request('DELETE', 'https://jsonplaceholder.typicode.com/posts/1')
//         ->map(fn($response) => $response->getStatusCode());

//     expect($task->run())->toBe(200);
// });
