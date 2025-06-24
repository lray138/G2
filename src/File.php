<?php

namespace lray138\G2;

use lray138\G2\{
    Either,
    Either\Left,
    Either\Right,
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

    public function putContents($contents) {
        $c = unwrap($contents); // get string or raw content
        $path = $this->getPath(); // target file path

        $result = @file_put_contents($path, $c);

        return $result === false
            ? Left::of("Unable to write to file: $path")
            : Right::of($result); // returns number of bytes written
    }
}
