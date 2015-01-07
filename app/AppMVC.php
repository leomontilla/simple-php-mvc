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
            new EjemploModule\EjemploModule()
        );
        
        return $modules;
    }
    
    public function setProviders()
    {
        $providers = array();
        
        $providers[] = array(
            'instance' => new EjemploModule\EjemploProvider(),
            'options'  => array()
        );
        
        return $providers;
    }
    
    public function getModules()
    {
        return $this->container->modules;
    }
}
