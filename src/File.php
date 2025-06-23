<?php

namespace lray138\G2;

use lray138\G2\{
    Either,
    Num,
    Common\Gonad
};

class File
{
    protected string $path;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function of(string $path)
    {
        if (file_exists($path)) {
            return Either::right(new static($path));
        }

        return Either::left("File does not exist: $path");
    }

    public function getBasename(): Str {
        return Str::of(basename($this->path));
    }

    public function getPath(): Str
    {
        return Str::of($this->path);
    }

    public function getSize()
    {
        $size = filesize($this->path);
        return $size === false
            ? Left::of('good error message')
            : Num::of($size);
    }

    public function getExtension(): Str
    {
        return Str::of(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function getContents()
    {
        $c = file_get_contents($this->path);
        return $c === false
            ? Left::of('good error message')
            : Str::of($c);
    }
}
