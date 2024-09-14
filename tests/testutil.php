<?php declare(strict_types=1);

namespace Tests;

function invokePrivateConstructor(string $className, array $args): mixed
{
    $constructor = (function (array $args) use ($className) {
        return new $className(...$args);
    })->bindTo(null, $className);

    return $constructor($args);
}

function deepClone(mixed $target): mixed
{
    return unserialize(serialize($target));
}
