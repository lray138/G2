<?php

namespace lray138\G2;

class Err extends \Exception
{
    private $input;

    public function __construct($message, $input = null)
    {
        parent::__construct($message);
        $this->input = $input;
    }

    public static function of($data): self
    {
        if (is_array($data)) {
            if (!isset($data['message'])) {
                throw new \Exception('Err::of requires a message');
            }
            return new self($data['message'], $data['input'] ?? null);
        }
        return new self($data);
    }

    public function getInput() {
        return $this->input;
    }
}