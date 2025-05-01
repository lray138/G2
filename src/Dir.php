<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad};
use lray138\G2\Either\Left;

class Dir
{
    protected $value;

    public function __construct(string $path)
    {
        $this->value = Arr::of([
            'path' => $path
        ]);
    }

    public static function of(string $path)
    {
        if (is_dir($path)) {
            return new static($path);
        }

        return Left::of("Directory does not exist: $path");
    }

    // Lazy load all children (both files and directories)
    public function getChildren(): IO
    {
        return new IO(fn()
            => $this->extract()
                ->prop('children')
                ->either(
                    fn() => Arr::of(scandir($this->extract()->prop('path')->extract())),
                    fn($xs) => Arr::of($xs)
                ));
    }

    // Get files by filtering children
    public function getFiles(): IO
    {
        return $this->getChildren()->map(function ($children) {
            return array_filter($children, function ($item) {
                return is_file($this->value['path'] . DIRECTORY_SEPARATOR . $item);
            });
        });
    }

    // Get directories by filtering children
    public function getDirs(): IO
    {
        return $this->getChildren()->map(function ($children) {
            return array_filter($children, function ($item) {
                return is_dir($this->value['path'] . DIRECTORY_SEPARATOR . $item) && $item != '.' && $item != '..';
            });
        });
    }

    public function extract()
    {
        return $this->value;
    }
}
