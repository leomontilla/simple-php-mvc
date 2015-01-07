<?php

use MVC\MVC;

/**
 * Description of AppMVC
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class AppMVC extends MVC
{
    
    public function setModules()
    {
        $modules = array(
            new \App\Module\EjemploModule\AppEjemploModule()
        );
        
        return $modules;
    }
    
    public function setProviders()
    {
        $providers = array();
        
        $providers[] = array(
            'instance' => new App\Module\EjemploModule\EjemploProvider(),
            'options'  => array()
        );
        
        return $providers;
    }
    
    public function getModules()
    {
        return $this->container->modules;
    }
}
