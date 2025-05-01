<?php

namespace lray138\G2;

use FunctionalPHP\FantasyLand\{Apply, Monad};
use lray138\G2\Either\Left;

class File
{
    protected $value;

    private function __construct(string $path)
    {
        $this->value = Arr::of([
            'path' => $path
        ]);
    }

    public static function of(string $path)
    {
        if (file_exists($path)) {
            return new static($path);
        }

        return Left::of("File does not exist: $path");
    }

    // Get the size of the file
    public function getSize(): IO
    {
        return new IO(fn() => filesize($this->extract()->prop('path')->extract()));
    }

    // Get the file extension
    public function getExtension(): IO
    {
        return new IO(fn() => pathinfo($this->extract()->prop('path')->extract(), PATHINFO_EXTENSION));
    }

    // Read the contents of the file
    public function read(): IO
    {
        return new IO(fn() => file_get_contents($this->extract()->prop('path')->extract()));
    }

    // Extract the internal value (path)
    public function extract()
    {
        return $this->value;
    }
}
