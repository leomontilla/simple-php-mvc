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
        $providers = array(
            array(
                'instance' => new \MVC\Providers\DoctrineDBALProvider(),
                'options'  => array(
                    'charset'  => null,
                    'driver'   => 'pdo_mysql',
                    'dbname'   => 'test',
                    'host'     => 'localhost',
                    'user'     => 'root',
                    'password' => null,
                    'port'     => null,
                )
            ),
            array(
                'instance' => new \MVC\Providers\DoctrineORMProvider(),
                'options' => array(
                    'params'       => array(
                        'charset'  => null,
                        'driver'   => 'pdo_mysql',
                        'dbname'   => 'test',
                        'host'     => 'localhost',
                        'user'     => 'root',
                        'password' => null,
                        'port'     => null,
                    ),
                    'dev_mode'     => false,
                    'etities_type' => 'annotations',
                    'path_entities' => array(
                        $this->getAppDir() . '/../src/EjemploModule/Entity'
                    ),
                    'proxy_dir'    => null
                )
            ),
            array(
                'instance' => new \MVC\Providers\MonologProvider(),
                'options' => array()
            ),
            array(
                'instance' => new \MVC\Providers\TwigProvider(),
                'options' => array(
                    'path' => $this->getAppDir() . '/../src/EjemploModule/Resources/views'
                )
            )
        );
        
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
