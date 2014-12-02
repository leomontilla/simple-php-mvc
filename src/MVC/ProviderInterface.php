<?php

namespace MVC;

/**
 * Provider Interface
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
interface ProviderInterface
{
    /**
     * Register the provider
     * 
     * @param MVC $app
     */
    public function register(MVC $app, array $options);
    
    /**
     * Bootstrap the application
     * 
     * @param MVC $app
     */
    public function boot(MVC $app);
}
