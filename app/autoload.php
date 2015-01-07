<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->addClassMap(array(
    'AppMVC' => __DIR__ . '/AppMVC.php'
));
$loader->add('App', __DIR__ . '/../src');