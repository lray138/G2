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

    public static function of($path): Either
    {
        $path = unwrap($path);
        if (file_exists($path)) {
            return Either::right(new static($path));
        }
        return Either::left("File does not exist: $path");
    }

    public static function either($path) {
        $path = unwrap($path);
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

    public function dirname(): Str {
        return Str::of(dirname($this->getPath()));
    }

    public function getSize(): Either
    {
        $size = @filesize($this->path);
        return $size === false
            ? Either::left('Unable to get file size: ' . $this->path)
            : Either::right(Num::of($size));
    }

    public function getExtension(): Str
    {
        return Str::of(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function getContents(): Either
    {
        $c = @file_get_contents($this->path);
        return $c === false
            ? Either::left('Unable to read file: ' . $this->path)
            : Either::right(Str::of($c));
    }

    public function putContents($contents): Either {
        $c = unwrap($contents); // get string or raw content
        $path = $this->getPath(); // target file path
        $result = @file_put_contents($path, $c);
        return $result === false
            ? Either::left("Unable to write to file: $path")
            : Either::right($result); // returns number of bytes written
    }
}
