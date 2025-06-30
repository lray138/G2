<?php

namespace lray138\G2;

class Err
{
    private $value;

    public function __construct(Kvm $err)
    {
        $this->value = $err;
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

    public function getMessage(): Str {
        return $this->value->prop("message");
    }

    public function getInput() {
        return $this->input;
    }
}