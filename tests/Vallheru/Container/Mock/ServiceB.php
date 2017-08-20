<?php
namespace Vallheru\Container\Mock;


class ServiceB
{
    private $a;

    public function __construct(ServiceA $serviceA)
    {
        $this->a = $serviceA;
    }
}