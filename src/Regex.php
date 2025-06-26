<?php

namespace lray138\G2;

use lray138\G2\{
    Either,
    Lst,
    Str
};

class Regex
{
    private string $pattern;
    
    private function __construct(string $pattern) {
        $this->pattern = $pattern;
    }
    
    public static function of(string $pattern): Either {
        if (@preg_match($pattern, '') === false) {
            return Either::left("Invalid regex pattern: $pattern");
        }
        return Either::right(new static($pattern));
    }
    
    public function match(string $subject): Either {
        $matches = [];
        $result = @preg_match($this->pattern, $subject, $matches);
        
        if ($result === false) {
            return Either::left("Regex match failed");
        }
        
        return $result === 1 
            ? Either::right(Lst::of($matches))
            : Either::left("No match found");
    }
    
    public function matchAll(string $subject, int $flags = 0): Either {
        $matches = [];
        $result = @preg_match_all($this->pattern, $subject, $matches, $flags);
        
        if ($result === false) {
            return Either::left("Regex match_all failed");
        }
        
        return Either::right(Lst::of($matches));
    }
    
    public function replace(string $subject, string $replacement): Either {
        $result = @preg_replace($this->pattern, $replacement, $subject);
        
        if ($result === null) {
            return Either::left("Regex replace failed");
        }
        
        return Either::right(Str::of($result));
    }
    
    public function split(string $subject, int $limit = -1): Either {
        $result = @preg_split($this->pattern, $subject, $limit);
        
        if ($result === false) {
            return Either::left("Regex split failed");
        }
        
        return Either::right(Lst::of($result));
    }
    
    public function test(string $subject): Either {
        $result = @preg_match($this->pattern, $subject);
        
        if ($result === false) {
            return Either::left("Regex test failed");
        }
        
        return Either::right(Boo::of($result === 1));
    }
    
    public function getPattern(): Str {
        return Str::of($this->pattern);
    }
} 