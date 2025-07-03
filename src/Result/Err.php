<?php

namespace lray138\G2\Result;

use FunctionalPHP\FantasyLand\{Apply, Functor, Monad};
use lray138\G2\Result;
use lray138\G2\Kvm;

class Err extends Result
{
    private $error;

    private function __construct($error)
    {
        $this->error = $error;
    }

    public static function of($data): self
    {
        
        if(is_string($data)) {
            return new self(Kvm::mempty()->set("message", $data));
        }
        
        if (is_array($data)) {
            if (!isset($data['message'])) {
                throw new \Exception('Err::of requires a message');
            }

            $kvm = Kvm::mempty()->set("message", $data['message']);

            if(isset($data['input'])) {
                $kvm = $kvm->set("input", $data["input"]);
            }

            return new self($kvm);
        }

        throw new \Exception('Err::of requires a message');
    }

    public function map(callable $f): Result
    {
        return $this;
    }

    public function bind(callable $f): Result
    {
        return $this;
    }

    public function ap(Apply $result): Result
    {
        return $this;
    }

    public function isOk(): bool
    {
        return false;
    }

    public function isErr(): bool
    {
        return true;
    }

    public function getOrElse($default)
    {
        return $default;
    }

    public function fold(callable $onOk, callable $onErr)
    {
        return $onErr($this->error);
    }

    public function mapErr(callable $f): Result
    {
        return Err::of($f($this->error));
    }

    public function extract()
    {
        return $this->error;
    }

    public function get()
    {
        return $this->extract();
    }

    public function getMessage(): Str
    {
        return $this->error->prop("message");
    }

    public function getInput()
    {
        return $this->error->prop("input");
    }

    public function hasInput(): bool
    {
        return $this->error->has("input");
    }

    public function withInput($input): self
    {
        $newKvm = $this->error->set("input", $input);
        return new self($newKvm);
    }
} 