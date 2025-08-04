
<?php 

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Promise\Promise;

use lray138\G2\Task;
use lray138\G2\Kvm;
use lray138\G2\Str;

use GuzzleHttp\Promise\Create;

it('Task::get retrieves a post', function () {
    $task = Task::get('https://jsonplaceholder.typicode.com/posts/1')
        ->decodeJson()
        ->map(fn(Kvm $Kvm) => $Kvm->prop('id'));

    expect($task->run()->get())->toBe(1);
});

it('Task::post creates a new post (simulated)', function () {
    $data = [
        'title' => 'foo',
        'body' => 'bar',
        'userId' => 1
    ];

    $task = Task::post('https://jsonplaceholder.typicode.com/posts', $data)
        ->decodeJson()
        ->map(fn(Kvm $Kvm) => $Kvm->prop('title'));

    expect($task->run())->toBeInstanceOf(Str::class);
    expect($task->run()->get())->toBe('foo');
});

it('Task::put updates a post (simulated)', function () {
    $data = [
        'id' => 1,
        'title' => 'updated',
        'body' => 'bar',
        'userId' => 1
    ];

    $task = Task::request('PUT', 'https://jsonplaceholder.typicode.com/posts/1', [
        'json' => $data
    ])
        ->decodeJson()
        ->map(fn(Kvm $Kvm) => $Kvm->prop('title')->get());

    expect($task->run())->toBe('updated');
});

it('Task::patch partially updates a post (simulated)', function () {
    $task = Task::request('PATCH', 'https://jsonplaceholder.typicode.com/posts/1', [
        'json' => ['title' => 'patched']
    ])
        ->decodeJson()
        ->map(fn(Kvm $Kvm) => $Kvm->maybeProp('title')->fold(fn($x) => $x, fn($x) => $x->get()));

    expect($task->run())->toBe('patched');
});

it('Task::delete removes a post (simulated)', function () {
    $task = Task::request('DELETE', 'https://jsonplaceholder.typicode.com/posts/1')
        ->map(fn($response) => $response->getStatusCode());

    expect($task->run())->toBe(200);
});

describe('Task Monad Laws', function () {

    it('satisfies Left Identity: Task::of(a)->bind(f) == f(a)', function () {
        $f = fn($x) => Task::of(fn() => Create::promiseFor($x + 1));
        $a = 2;

        $left = Task::of(fn() => Create::promiseFor($a))->bind($f)->run();
        $right = $f($a)->run();

        expect($left)->toBe($right);
    });

    it('satisfies Right Identity: m->bind(Task::of) == m', function () {
        $m = Task::of(fn() => Create::promiseFor(42));
    
        $left = $m->bind(fn($x) => Task::of(fn() => Create::promiseFor($x)))->run();
        $right = $m->run();
    
        expect($left)->toBe($right);
    });

    it('satisfies Associativity: m->bind(f)->bind(g) == m->bind(x => f(x)->bind(g))', function () {
        $m = Task::of(fn() => Create::promiseFor(5));
    
        $f = fn($x) => Task::of(fn() => Create::promiseFor($x + 1));
        $g = fn($x) => Task::of(fn() => Create::promiseFor($x * 2));
    
        $left = $m->bind($f)->bind($g)->run();
        $right = $m->bind(fn($x) => $f($x)->bind($g))->run();
    
        expect($left)->toBe($right);
    });

});


describe('Task Applicative Laws (ap)', function () {

    it('satisfies Associativity: (m->bind(f))->bind(g) == m->bind(x => f(x)->bind(g))', function () {
        $m = Task::of(fn() => Create::promiseFor(5));
    
        $f = fn(int $x) => Task::of(fn() => Create::promiseFor($x + 1));
        $g = fn(int $x) => Task::of(fn() => Create::promiseFor($x * 2));
    
        $left = $m->bind($f)->bind($g)->run();
        $right = $m->bind(fn($x) => $f($x)->bind($g))->run();
    
        expect($left)->toBe($right);
    });

    it('satisfies Homomorphism: Task::of(f)->ap(Task::of(x)) == Task::of(f(x))', function () {
        $f = fn($x) => $x * 3;
        $x = 4;
    
        $left = Task::of(fn() => Create::promiseFor($f))->ap(Task::of(fn() => Create::promiseFor($x)))->run();
        $right = Task::of(fn() => Create::promiseFor($f($x)))->run();
    
        expect($left)->toBe($right);
    });

    it('satisfies Interchange: Task::of(f)->ap(Task::of(x)) == Task::of(fn(f) => f(x))->ap(Task::of(f))', function () {
        $f = fn($x) => $x + 10;
        $x = 5;
    
        // Wrap the function and value in promises
        $u = Task::of(fn() => Create::promiseFor($f));
        $y = Task::of(fn() => Create::promiseFor($x));
    
        // Left side: applying f to x
        $left = $u->ap($y)->run();
    
        // Right side: applying f(x) inside another function and then applying
        $right = Task::of(fn() => Create::promiseFor(fn($g) => $g($x)))
            ->ap($u) // Pass the task itself, not its result
            ->run();
    
        // Check if both sides are equal
        expect($left)->toBe($right);
        
    });
    
    it('satisfies Composition: Task::of(compose)->ap(u)->ap(v)->ap(w) == u->ap(v->ap(w))', function () {
        $compose = fn($f) => fn($g) => fn($x) => $f($g($x));
    
        // Wrap with promises
        $f = fn($x) => $x + 2;
        $g = fn($x) => $x * 3;
        $x = 5;
    
        // All Tasks should return Promises
        $u = Task::of(fn() => Create::promiseFor($f));
        $v = Task::of(fn() => Create::promiseFor($g));
        $w = Task::of(fn() => Create::promiseFor($x));
    
        // Left: Task::of(compose)->ap(u)->ap(v)->ap(w)
        $left = Task::of(fn() => Create::promiseFor($compose))
            ->ap($u)
            ->ap($v)
            ->ap($w)
            ->run();
    
        // Right: u->ap(v->ap(w))
        $right = $u->ap($v->ap($w))->run();
    
        expect($left)->toBe($right);
    });
    
});
