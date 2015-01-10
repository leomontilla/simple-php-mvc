<?php

namespace MVC\Tests\Provider;

use Monolog\Logger,
    Monolog\Handler\StreamHandler,
    MVC\MVC,
    MVC\Provider\Provider;

/**
 * Monolog Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class MonologProvider extends Provider
{
    
    /**
     * Bootstrap of the Provider
     * @access public
     * @param MVC $app
     * @return void
     */
    public function boot(MVC $app) {}

    /**
     * Register the properties of the Monolog Provider
     * @access public
     * @param MVC $app
     * @param array $options
     * @return void
     */
    public function register(MVC $app, array $options)
    {
        $defaultOptions = array(
            'log_file' => './app.log',
            'log_name' => 'app_name'
        );
        
        $options = array_merge($defaultOptions, $options);
        
        $logger = new Logger($options['log_name']);
        $logger->pushHandler(new StreamHandler($options['log_file']));
        
//        $app->setKey('monolog', $logger);
    }

}
