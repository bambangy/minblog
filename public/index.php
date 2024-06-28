<?php

session_start();

require_once __DIR__ . '/../autoload.php';

$config = new bin\Config(__DIR__ . '/../.env');
$container = new bin\MinContainer();
$app = new bin\MinBlog($container);
$app->Run();
