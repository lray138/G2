<?php

namespace lray138\G2\Types;

use function lray138\GAS\dump;

function isNumber($variable): bool
{
    return is_integer($variable) || is_float($variable);
}

function isBoolean($variable): bool
{
    return is_bool($variable);
}

function isNull($variable)
{
    return is_null($variable) || $variable instanceof \lray138\G2\Maybe\Nothing
        || $variable instanceof \lray138\G2\Either\Left;
}

function isObject($variable): bool
{
    return is_object($variable) && !isFunction($variable);
}

function isFunction($var): bool
{
    if ($var instanceof \Closure) {
        return true;
    }

    if (is_string($var) && function_exists($var)) {
        return true;
    }

    return false;
    // processwire was messing this up and this is probably better (above)
    //return is_callable($variable) and is_object($variable);
}

function isExpression($variable)
{
    return isRegex($variable);
}

function isRegex($variable)
{
    // Sun Apr 17 2020 @ 19:44 got error because of the order of these things
    // when it was an array, and basically was getting an error when an array was passed...
    // so for now just putting if 1 string...

    // trimming with empty string throws an error here checking for regex
    // adding the empty(trim)
    if (!is_string($variable) || empty(trim($variable))) {
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
    $notVoidTag = implode([substr($variable, 0, 1), substr($variable, strlen($variable) - 1, 1)]) !== "<>";

    // Thu Feb 27 15:14 - "WOW"... this is that Chris Pitt code I believe and really just need to check if
    // first string is "/" or "|" otherwise ...
    // also seems like it.. it's failing on doctype so...

    $first_char = substr($variable, 0, 1);
    $last_char = substr($variable, strlen($variable) - 1, 1);

    if (!in_array($first_char, ["/", "|"])) {
        return false;
    }

    // wow... 2025-05-02 19:38:00

    $isNotFalse = @preg_match($variable, "") !== false;
    $hasNoError = preg_last_error() === PREG_NO_ERROR;
    return $isNotFalse and $hasNoError && $notVoidTag;
}

function isString($variable)
{
    return is_string($variable) and !isExpression($variable);
}

function isResource($variable)
{
    return is_resource($variable);
}

function isArray($variable)
{
    return is_array($variable);
}

function getType($variable)
{
    $functions = [
        "isNumber" => "number",
        "isBoolean" => "boolean",
        "isString" => "string",
        "isNull" => "null",
        "isObject" => "object",
        "isFunction" => "function",
        "isExpression" => "expression",
        "isResource" => "resource",
        "isArray" => "array"
    ];

    $result = "unknown";

    foreach ($functions as $function => $type) {
        $qualified = "lray138\\G2\\Types\\{$function}";

        if ($qualified($variable)) {
            $result = $type;
            break;
        }
    }

    return $result;
}

// function wrap($variable)
// {
//     return wrapType($variable);
// }

const wrap = __NAMESPACE__ . '\wrap';

function wrap($variable)
{
    if (is_object($variable)) {
        if (
            in_array(get_class($variable), [
                "lray138\G2\Kvm",
                "lray138\G2\Lst",
                "lray138\G2\Boo",
                "lray138\G2\Dir",
                "lray138\G2\Either\Left",
                "lray138\G2\Either\Right",
                "lray138\G2\File",
                "lray138\G2\IO",
                "lray138\G2\Maybe\Just",
                "lray138\G2\Maybe\Nothing",
                "lray138\G2\Num",
                "lray138\G2\Reader",
                "lray138\G2\State",
                "lray138\G2\Str",
                "lray138\G2\Task",
                "lray138\G2\Time",
                "lray138\G2\Writer"
            ])
        ) {
            return $variable;
        }
    }

    $result = "Error";

    $types = [
        "number" => "Num",
        "boolean" => "Boo",
        "null" => "Maybe\\Nothing", // changed from None
        "expression" => "Str",
        "string" => "Str", // was Str (it's StrType to avoid confusion with functions Str)
        "array" => "array"
    ];

    $type = getType($variable);

    $object_types = [
        "DateTime" => "\lray138\GAS\Types\Time"
        , "Moment\MomentFromVo" => "\lray138\GAS\Types\Time"
    ];

    if ($type === "object") {
        $class = get_class($variable);
        if (isset($object_types[$class])) {
            return $object_types[$class]::of($variable);
        }

        // this is not right
        //return call_user_func("\\lray138\\GAS\\Types\\" . $result . "::of", $class);
        return \lray138\G2\Either\Right::of($variable);
    }

    if (isset($types[$type])) {
        $result = $types[$type];
    } else {
        $variable = "Type for value '" . $variable . "' can not be determined";
    }

    if($type == "array" && count($variable) == 0) {
        return \lray138\G2\Lst::mempty();
    }

    if($type == "array") {
        return array_keys($variable) !== range(0, count($variable) - 1)
            ? \lray138\G2\Kvm::of($variable)
            : \lray138\G2\Lst::of($variable);
    }

    return call_user_func("\\lray138\\G2\\" . $result . "::of", $variable);
}

const wrapType = __NAMESPACE__ . '\wrapType';

// leaving a comment out for discussion purposes 

// function Arr($value = [])
// {
//     return ArrType::of($value);
// }

// const Arr = __NAMESPACE__ . '\Arr';

// function Many($value = null)
// {
//     return Many::of($value);
// }

// const Many = __NAMESPACE__ . '\Many';

// function Maybe($value = null)
// {
//     return Maybe::of($value);
// }

// const Maybe = __NAMESPACE__ . '\Maybe';

// function Some($value)
// {
//     return Some::of($value);
// }

// const Some = __NAMESPACE__ . '\Some';

// function Str($value = "")
// {
//     return StrType::of($value);
// }

// const Str = __NAMESPACE__ . '\Str';

// function None()
// {
//     return None::of();
// }

// const None = __NAMESPACE__ . '\None';

// function Nothing()
// {
//     return Nothing::of();
// }

// const Nothing = __NAMESPACE__ . '\Nothing';

// function Error($message)
// {
//     return Error::of($message);
// }

// const Error = __NAMESPACE__ . '\Error';

// function Either($value)
// {
//     return \lray138\GAS\Types\Either::of($value);
// }

// const Either = __NAMESPACE__ . '\Either';

// function Left($message)
// {
//     $out = \lray138\GAS\Types\Either\Left::of($message);
//     return $out;
// }

// const Left = __NAMESPACE__ . '\Left';

// function Right($value)
// {
//     return \lray138\GAS\Types\Either\Right::of($value);
// }

// const Right = __NAMESPACE__ . '\Right';


// function Model()
// {
//     $f = function ($table, $db) {
//         return new Model($table, $db);
//     };

//     return \lray138\GAS\Functional\curry2($f)(...func_get_args());
// }

// function Calendar()
// {
//     return \lray138\GAS\Types\Calendar::of();
// }

// function Time($datetime = null)
// {
//     return \lray138\GAS\Types\Time::of($datetime);
// }

// const Time = __NAMESPACE__ . '\Time';

// function Number($number = null)
// {
//     return is_null($number)
//         ? \lray138\GAS\Types\Number::of(0)
//         : \lray138\GAS\Types\Number::of($number);
// }

// function Boolean($boolean)
// {
//     return \lray138\GAS\Types\Boolean::of($boolean);
// }
