<?php

namespace lray138\G2;

use lray138\G2\{
    Either\Left,
    Lst,
    Str,
    Common\GonadTrait
};

class Dir
{
    use GonadTrait;

    protected string $path;

    private function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR); // Normalize
    }

    public static function of(string $path)
    {
        if (is_dir($path)) {
            return new static($path);
        }

        return Left::of("Directory does not exist: $path");
    }

    public function getPath(): Str
    {
        return Str::of($this->path);
    }

    public function getChildren()
    {
        $children = scandir($this->path);

        if ($children === false) {
            return Left::of("Unable to read directory: {$this->path}");
        }

        $entries = array_values(array_diff($children, ['.', '..']));
        $wrapped = array_map(fn($s) => Str::of($s), $entries);

        return Lst::of($wrapped);
    }

    public function getFiles()
    {
        return $this->getChildren()->filter(
            fn($item) => is_file($this->path . DIRECTORY_SEPARATOR . unwrap($item))
        );
    }

    public function getDirs()
    {
        $children = $this->getChildren();

        return $children->map(fn($items) =>
            array_filter($items, fn($item) =>
                is_dir($this->path . DIRECTORY_SEPARATOR . $item)));
    }

    public function getTree(): Arr
    {
        $descendants = [];

        // Recursive function to traverse through directories
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $fileinfo) {
            $descendants[] = $fileinfo->getRealPath();
        }

        return Lst::of($descendants);
    }

    public function getDirsRecursive(): Arr
    {
        $dirs = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDir()) {
                $dirs[] = $fileinfo->getRealPath();
            }
        }

        return Lst::of($dirs);
    }

    public function getFilesRecursive(): Arr
    {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile()) {
                $files[] = $fileinfo->getRealPath();
            }
        }

        return Lst::of($files);
    }

    use \lray138\G2\Common\ExtendTrait;
}