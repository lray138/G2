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

    public static function of($path)
    {
        $path = unwrap($path);
        if (file_exists($path)) {
            return new static($path);
        }

        throw new \Exception("File does not exist: $path");
    }

    public static function either($path) {
        $path = unwrap($path);
        if (file_exists($path)) {
            return Either::right(new static($path));
        }

        return Either::left(Str::of("File does not exist: $path"));
    }

    public static function result($path) {
        $path = unwrap($path);
        if (file_exists($path)) {
            return Result::ok(new static($path));
        }

        return Result::err(["message" => "File does not exist: $path", "input" => $path]);
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
            : Either::right($this); // returns number of bytes written
    }

    public function exists() {
        // TODO: Implement
    }

    public function delete() {
        if (!file_exists($this->path)) {
            return \lray138\G2\Either::left('File does not exist: ' . $this->path);
        }
        if (@unlink($this->path)) {
            return \lray138\G2\Either::right(true);
        } else {
            return \lray138\G2\Either::left('Failed to remove file: ' . $this->path);
        }
    }

    public function copy($destination) {
        // Accept Dir, File, or string
        if ($destination instanceof \lray138\G2\Dir) {
            $destPath = rtrim($destination->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($this->path);
        } elseif ($destination instanceof self) {
            $destPath = $destination->getPath();
        } elseif (is_string($destination)) {
            if (is_dir($destination)) {
                $destPath = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($this->path);
            } else {
                $destPath = $destination;
            }
        } else {
            return \lray138\G2\Either::left('Invalid destination type');
        }

        // Attempt to copy
        if (@copy($this->path, $destPath)) {
            return \lray138\G2\Either::right(self::of($destPath));
        } else {
            return \lray138\G2\Either::left('Failed to copy file');
        }
    }

    public function move($destination) {
        // TODO: Implement
    }

    public function touch() {
        // TODO: Implement
    }

    public function isReadable() {
        // TODO: Implement
    }

    public function isWritable() {
        // TODO: Implement
    }

    public function isExecutable() {
        // TODO: Implement
    }

    public function getMTime() {
        // TODO: Implement
    }

    public function getATime() {
        // TODO: Implement
    }

    public function getCTime() {
        // TODO: Implement
    }

    public function getOwner() {
        // TODO: Implement
    }

    public function getGroup() {
        // TODO: Implement
    }

    public function getPerms() {
        // TODO: Implement
    }

    public function getType() {
        // TODO: Implement
    }

    public function __toString()
    {
        return $this->path;
    }
}
