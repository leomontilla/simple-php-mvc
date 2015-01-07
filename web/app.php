<?php

require_once __DIR__ . '/../app/autoload.php';

$mvc = new AppMVC(array(
    'debug' => true
));

$mvc->get('/', function() use($mvc) {
    return 'Index';
}, 'index');

$mvc->get('/modules', function() use($mvc) {
    var_dump($mvc->getModules());
    return 'asd';
});

$mvc->run();