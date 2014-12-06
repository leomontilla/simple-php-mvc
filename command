#!/usr/bin/env php
<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('MVC', __DIR__ . "/src");

/*
$settings = array(
    'app_path' => './app'
    'views_path' => './Views',
    'models_path' => './Models',
    'controllers_path' => './Controllers'
);
$command = new MVC\Command\Command(__DIR__, $settings);
*/

$command = new MVC\Command\Command(__DIR__);
$command->run($argv);