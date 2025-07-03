<?php

namespace lray138\G2;

use lray138\G2\{
    Either,
    Maybe,
    Lst,
    Str,
    Common\GonadTrait
};

use function PHPUnit\Framework\throwException;

class Dir
{
    use GonadTrait;

    protected string $path;

    private function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR); // Normalize
    }

    public static function of($path)
    {
        if (is_dir($path)) {
            return new static($path);
        }

        throw new \InvalidArgumentException("Directory does not exist: {$path}");
    }

    public static function either($path)
    {
        $path = unwrap($path);
        if (is_dir($path)) {
            return Either::right(new static($path));
        }

        return Either::left("Somethign went wrong");
    }

    public static function maybe($path)
    {
        $path = unwrap($path);
        if (is_dir($path)) {
            return Maybe::just(new static($path));
        }

        return Maybe::nothing();
    }

    public function getPath(): Str
    {
        return Str::of($this->path);
    }

    public static function getOrCreate($path): Dir {
        if (is_dir($path)) {
            return Dir::of($path);
        }

        if (@mkdir($path, 0777, true)) {
            return Dir::of($path);
        }

        throw new \Exception("Failed to create directory at $path");
    }

    public function getChildren(): Either
    {
        $children = @scandir($this->path);
        if ($children === false) {
            return Either::left("Unable to read directory: {$this->path}");
        }
        $entries = array_values(array_diff($children, ['.', '..']));
        $wrapped = array_map(function($x) {
            $fullPath = $this->path . '/' . $x;
            
            if (is_file($fullPath)) {
                return File::of($fullPath);
            }

            if (is_dir($fullPath)) {
                return Dir::of($fullPath);
            }

            return Str::of($fullPath);
        }, $entries);

        $lst = Lst::of($wrapped);

        return Either::right($lst);
    }

    public function getFiles(): Either
    {
        return $this->getChildren()->map(
            fn($lst) => $lst->filter(fn($item) => $item instanceof File)
        );
    }

    public function getDirs(): Either
    {
        return $this->getChildren()->map(
            fn($lst) => $lst->filter(fn($item) => $item instanceof Dir)
        );
    }

    public function getTree(): Either
    {
        $descendants = [];
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $fileinfo) {
                $descendants[] = $fileinfo->getRealPath();
            }
            return Either::right(Lst::of($descendants));
        } catch (\Exception $e) {
            return Either::left($e->getMessage());
        }
    }

    public function getDirsRecursive(): Either
    {
        $dirs = [];
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDir()) {
                    $dirs[] = $fileinfo->getRealPath();
                }
            }
            return Either::right(Lst::of($dirs));
        } catch (\Exception $e) {
            return Either::left($e->getMessage());
        }
    }

    public function getFilesRecursive(): Either
    {
        $files = [];
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isFile()) {
                    $files[] = File::of($fileinfo->getRealPath())->get();
                }
            }

            return Either::right(Lst::of($files));
        } catch (\Exception $e) {
            return Either::left($e->getMessage());
        }
    }

    use \lray138\G2\Common\ExtendTrait;
}