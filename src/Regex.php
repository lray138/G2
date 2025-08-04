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

    public static function isValid(string $variable)
{
    // Sun Apr 17 2020 @ 19:44 got error because of the order of these things
    // when it was an array, and basically was getting an error when an array was passed...
    // so for now just putting if 1 string... 

    // trimming with empty string throws an error here checking for regex
    // adding the empty(trim)
    if(!is_string($variable) || empty(trim($variable))) {
        return false;
    }

    // this was choking on <!doctype html> with a line break, 
    // I still think adding the check for "/" or "|" is good too though
    $variable = trim($variable);

    // Oct 9, 2024 @ 17:34 - adding this for edge cases.
    if (preg_match('/[a-zA-Z0-9]/', substr($variable, 0, 1)) || preg_match('/[a-zA-Z0-9]/', substr($variable, -1))) {
        return false; // Not a valid regex since the start/end characters are not symbols
    }

    // I added this because it was choking on "<hr>" for HTML cleanup
    $notVoidTag = implode([substr($variable, 0, 1), substr($variable, strlen($variable)-1, 1)]) !== "<>";

    // Thu Feb 27 15:14 - "WOW"... this is that Chris Pitt code I believe and really just need to check if 
    // first string is "/" or "|" otherwise ...
    // also seems like it.. it's failing on doctype so...

    $first_char = substr($variable, 0, 1);
    $last_char = substr($variable, strlen($variable)-1, 1);

    if(!in_array($first_char, ["/", "|"])) {
        return false;
    }
    
    $isNotFalse = @preg_match($variable, "") !== false;
    $hasNoError = preg_last_error() === PREG_NO_ERROR;
    return $isNotFalse and $hasNoError && $notVoidTag;
}
    
    public static function of(string $pattern): self {
        
        if(!self::isValid($pattern)) { 
            throw new \InvalidArgumentException("Invalid regex pattern: $pattern");
        }
        // if (@preg_match($pattern, '') === false) {
        //     throw new \InvalidArgumentException("Invalid regex pattern: $pattern");
        // }
        return new static($pattern);
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