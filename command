<?php
spl_autoload_register(function($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    require "lib/$file";
});
$command = new \MVC\command\Command(__DIR__);
$command->run($argv);