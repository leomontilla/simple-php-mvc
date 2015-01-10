<?php

use MVC\MVC;
use MVC\Module\Module;
use MVC\Provider\Provider;
use MVC\Server\Route;

/**
 * Description of AppMVC
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class AppMVC extends MVC
{
    
    /**
     * Set MVC Application Modules
     * 
     * @return Module[]
     */
    public function setModules()
    {
        $modules = array(
            new \MVC\Tests\EjemploModule\EjemploModule(),
        );
        
        return $modules;
    }
    
    /**
     * Set MVC Application Providers
     * 
     * @return Provider[]
     */
    public function setProviders()
    {
        $providers = array(
            array(
                'instance' => new \MVC\Tests\Provider\DoctrineDBALProvider(),
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
                'instance' => new \MVC\Tests\Provider\DoctrineORMProvider(),
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
                'instance' => new \MVC\Tests\Provider\MonologProvider(),
                'options' => array()
            ),
            array(
                'instance' => new \MVC\Tests\Provider\TwigProvider(),
                'options' => array(
                    'path' => $this->getAppDir() . '/../src/EjemploModule/Resources/views'
                )
            )
        );
        
        $providers[] = array(
            'instance' => new \MVC\Tests\EjemploModule\EjemploProvider(),
            'options'  => array()
        );
        
        return $providers;
    }
    
    /**
     * Set MVC Application Routes
     * 
     * @return Route[]
     */
    public function setRoutes()
    {
        $routes = parent::setRoutes();
        
        return $routes;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getModules()
    {
        return $this->container->getModules();
    }
}
