<?php

namespace lray138\G2;

// https://stackoverflow.com/questions/933367/php-how-to-best-determine-if-the-current-invocation-is-from-cli-or-web-server
function isCLI()
{
    if (defined('STDIN')) {
        return true;
    }

    if (php_sapi_name() === 'cli') {
        return true;
    }

    if (array_key_exists('SHELL', $_ENV)) {
        return true;
    }

    if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
        return true;
    }

    if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
        return true;
    }

    return false;
}

function dump()
{
    $args = count(func_get_args()) > 0
        ? func_get_args()
        : [null];

    if (!isCLI()) {
        echo "<pre>";
        var_dump(...$args);
        echo "</pre>";
    } else {
        var_dump(...$args);
    }

    return $args[0];
}
