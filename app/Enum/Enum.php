<?php

namespace App\Enum;

use ReflectionException;

class Enum
{
    /**
     * @throws ReflectionException
     */
    public static function getConstants($class): array
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->getConstants();
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getLocalConstants(): array
    {
        return static::getConstants(static::class);
    }
}
