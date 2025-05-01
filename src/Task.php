<?php 

namespace lray138\G2;

use lray138\G2\Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use \FunctionalPHP\FantasyLand\{Apply, Monad};

class Task implements Monad
{
    private $action;

    private function __construct(callable $action)
    {
        $this->action = $action;
    }

    public function ap(Apply $a): self {
        return $this->bind(fn($f) => $a->map($f));
    }

    public static function of($fn): self
    {
        return new self($fn);
    }

    public static function request(string $method, string $url, array $options = []): self
    {
        return new self(function () use ($method, $url, $options) {
            $client = new Client();
            $out = $client->requestAsync($method, $url, $options);
            return $out;
        });
    }

    public static function get(string $url, array $options = []): self
    {
        return self::request('GET', $url, $options);
    }

    public static function post(string $url, array $data = [], array $options = []): self
    {
        $options['json'] = $data;
        return self::request('POST', $url, $options);
    }

    public function map(callable $fn): self
    {
        return new self(function () use ($fn) {
            /** @var PromiseInterface $promise */
            $promise = ($this->action)();
            return $promise->then($fn);
        });
    }

    public function bind(callable $fn): self
    {
        return new self(function () use ($fn) {
            /** @var PromiseInterface $promise */
            $promise = ($this->action)();
            return $promise->then(function ($result) use ($fn) {
                $nextTask = $fn($result);
                return $nextTask instanceof self
                    ? ($nextTask->action)()
                    : $result;
            });
        });
    }

    public function decodeJson() {
        return $this->map(fn($resp) => Arr::of(json_decode($resp->getBody(), true)));
    }

    public function fork(callable $onReject, callable $onResolve)
    {
        return ($this->action)()
            ->then($onResolve, $onReject)
            ->wait();
    }

    // at first this was returning a #PromiseInterface then a \GuzzleHttp\Psr7\Response
    public function run() {   
        $out = ($this->action)()->wait();
        return $out;
    }

}