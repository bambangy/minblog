<?php

namespace bin;

use bin\MinContainer;

class Controller
{
    private MinContainer $container;
    public function __construct(MinContainer $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        if ($this->container->$name)
            return $this->container->$name;

        return $this->$name;
    }
}
