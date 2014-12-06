<?php

$loader = require_once __DIR__ . '/vendor/autoload.php';

$app = new MVC\MVC();

$app->registerProvider(new MVC\Providers\ExampleProvider());

$app->registerProvider(new MVC\Providers\DoctrineDBALProvider(), array(
    'driver'   => 'pdo_mysql',
    'dbname'   => 'precursorbd',
    'host'     => '127.0.0.1',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8',
));

$app->registerProvider(new MVC\Providers\DoctrineORMProvider(), array(
    'path_entities' => array(__DIR__ . '/entities')
));

$app->registerProvider(new MVC\Providers\MonologProvider(), array(
    
));

$app->registerProvider(new MVC\Providers\TwigProvider(), array(
    'path' => __DIR__ . '/templates',
));

$app->get('/', function() use($app) {
    return $app->view()->render('index.html', array(
        'dbal' => $app->generateUrl('dbal', false),
        'orm' => $app->generateUrl('orm', false),
        'monolog' => $app->generateUrl('monolog', false),
        'twig' => $app->generateUrl('twig', false),
    ));
}, 'index');

$app->get('/dbal', function() use($app) {
    ob_start();
    print '<pre>';
    var_dump($app->getKey('dbal'));
    return ob_get_clean();
}, 'dbal');

$app->get('/orm', function() use($app) {
    ob_start();
    print '<pre>';
    var_dump($app->getKey('em'));
    return ob_get_clean();
}, 'orm');

$app->get('/monolog', function() use($app) {
    ob_start();
    print '<pre>';
    var_dump($app->getKey('monolog'));
    return ob_get_clean();
}, 'monolog');

$app->get('/twig', function() use($app) {
    return $app->getKey('twig')->render('index.html.twig');
}, 'twig');

$app->run();