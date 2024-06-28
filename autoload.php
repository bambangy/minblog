<?php
spl_autoload_register(function ($class) {
    //$prefix = '';
    $baseDir = __DIR__;

    //$len = strlen($prefix);
    // if (strncmp($prefix, $class, $len) !== 0) {
    //     // the class does not use the namespace prefix
    //     return;
    // }

    //$relativeClass = substr($class, $len);
    $file = $baseDir . "/" . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("File $file not found");
    }
});
