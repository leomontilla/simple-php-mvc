<?php

/**
 * Provider Interface
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */

namespace MVC;

interface ProviderInterface
{    
    /**
     * Bootstrap of the provider
     * @access public
     * @param MVC $app
     */
    public function boot(MVC $app);
    
    /**
     * Register the provider properties and functions
     * @access public
     * @param MVC $app
     */
    public function register(MVC $app, array $options);
}
