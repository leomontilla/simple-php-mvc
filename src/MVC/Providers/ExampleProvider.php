<?php

/**
 * Description of ExampleProvider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\Providers;

use MVC\MVC,
    MVC\ProviderInterface;

class ExampleProvider implements ProviderInterface
{
    
    public function boot(MVC $app) {
        print "Boot" . $app->getKey('example_name');
    }

    public function register(MVC $app, array $options = array()) {
        
        $app->setKey('example.name', get_class($this));
        
        print "Register Example Provider";
        
    }

}
