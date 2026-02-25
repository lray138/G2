<?php

declare(strict_types=1);

namespace lray138\G2\Common;

use lray138\G2\Nil;

use function lray138\G2\{
    wrap,
    unwrap
};

use lray138\G2\{Either, Maybe, Result};

trait GetPropTrait
{
    public function expect($key)
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            throw new \Exception("prop '$key' not found");
        }

        return wrap($stored[$key]);
    }

    public function key($key) {
        return $this->prop($key);
    }

    public function prop($key)
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (is_string($key) && $this->isPathLike($key)) {
            return $this->pathProp($stored, $key);
        }

        if (!is_array($stored) || !array_key_exists($key, $stored)) {
            return Nil::unit();
        }

        return wrap($stored[$key]);
    }

    public function tryProp($key): Result
    {
        $value = $this->prop($key);
        $unwrappedKey = unwrap($key);
        $isPath = is_string($unwrappedKey) && $this->isPathLike($unwrappedKey);
        $notFound = $isPath
            ? "path '$unwrappedKey' not found"
            : "prop '$unwrappedKey' not found";

        return $value instanceof Nil
            ? Result::err($notFound)
            : Result::ok($value);
    }

    private function pathProp($stored, string $path)
    {
        $parts = $this->splitPath($path);
        $current = $stored;

        foreach ($parts as $part) {
            $current = unwrap($current);
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return Nil::unit();
            }
            $current = $current[$part];
        }

        return wrap($current);
    }

    private function isPathLike(string $key): bool
    {
        return str_contains($key, '/')
            || str_contains($key, '.')
            || str_contains($key, '[');
    }

    private function splitPath(string $path): array
    {
        $parts = preg_split('/[\/.]+/', trim($path));
        $parts = array_values(array_filter($parts, fn($part) => $part !== ''));

        $segments = [];
        foreach ($parts as $part) {
            preg_match_all('/([^\[\]]+)|\[(\d+)\]/', $part, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $segments[] = $match[1] !== '' ? $match[1] : $match[2];
            }
        }

        return $segments;
    }

    public function maybeProp($key): Maybe
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            return Maybe::nothing();
        }

        return Maybe::just(wrap($stored[$key]));
    }

    public function mProp($key): Maybe
    {
       return $this->maybeProp($key);
    }

    public function eitherProp($key): Either
    {
        $stored = unwrap($this->extract());
        $key = unwrap($key);

        if (!isset($stored[$key])) {
            return Either::left("prop '$key' not found");
        }

        return Either::right(wrap($stored[$key]));
    }

    public function eProp($key): Either
    {
        return $this->eitherProp($key);
    }

    public function pluck($key) {
        return $this->prop($key);
    }
    
}