<?php

namespace Configs;

class Dsn
{
    public static function Get()
    {
        return [
            "driver" => getenv("db_driver"),
            "server" => getenv("db_server"),
            "username" => getenv("db_username"),
            "password" => getenv("db_password"),
            "database" => getenv("db_name"),
            "charset" => getenv("db_charset")
        ];
    }
}
