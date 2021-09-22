<?php

require_once 'conf.php';
require_once 'func.php';
require_once 'myRedis.php';
require_once 'myView.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $classFile =  __DIR__ . '/../'.$class . '.php';
    if (is_file($classFile)) {
        require_once($classFile);
    }
    return true;
});