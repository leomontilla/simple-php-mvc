<?php

/**
 * Example Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\Providers
 */

namespace MVC\Tests\Provider;

use MVC\MVC,
    MVC\Provider\Provider;

class ExampleProvider extends Provider
{
    
    /**
     * Bootstrap of the Provider
     * @access public
     * @param MVC $app
     * @return void
     */
    public function boot(MVC $app)
    {
        // Bootstrap of the provider
    }

    /**
     * Register the properties of the Doctrine DBAL Provider
     * @access public
     * @param MVC $app
     * @param array $options
     * @return void
     */
    public function register(MVC $app, array $options = array())
    {
        // Register the properties and functions of the provider
//        $app->setKey('example', get_class($this));
    }

}
