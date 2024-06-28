<?php

namespace bin;

class MinContainer
{
    private array $cont = [];

    public function __construct()
    {
        $this->cont['TempData'] = [];
    }

    public function Set(string $name, mixed $object)
    {
        $this->cont[$name] = $object;
    }

    public function __get($name)
    {
        if (isset($this->cont[$name]))
            return $this->cont[$name];
        return null;
    }
}
