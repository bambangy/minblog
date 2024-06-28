<?php

namespace bin;

use Configs\Dsn;
use Models\WebSession;

class MinBlog
{
    private MinContainer $container;
    public function __construct(MinContainer $container = null)
    {
        $this->container = $container;
        $this->container->Set('view', new ViewHandler(__DIR__ . "/../views/"));
        $db = new Database(Dsn::Get());
        $this->container->Set('db', $db);
        $session = new WebSession($this->container->db);
        $this->container->Set('session', $session);
    }

    public function Run()
    {
        Router::Route($this->container);
    }

    public function __get($name)
    {
        return $this->container->$name;
    }
}
