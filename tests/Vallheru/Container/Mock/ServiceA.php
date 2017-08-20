<?php

namespace Vallheru\Container\Mock;


class ServiceA
{
    private static $_random = 0;

    private $random;

    public function __construct()
    {
        $this->random = self::$_random++;
    }

    public function getRandom() : int
    {
        return $this->random;
    }
}