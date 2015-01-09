<?php

/**
 * Doctrine DBAL Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\Providers
 */

namespace MVC\Tests\Provider;

use Doctrine\DBAL\Configuration,
    Doctrine\DBAL\DriverManager,
    MVC\MVC,
    MVC\Provider\Provider;

class DoctrineDBALProvider extends Provider
{
    
    /**
     * Bootstrap of the Provider
     * @access public
     * @param MVC $app
     * @return void
     */
    public function boot(MVC $app) {}

    /**
     * Register the properties of the Doctrine DBAL Provider
     * @access public
     * @param MVC $app
     * @param array $options
     * @return void
     */
    public function register(MVC $app, array $options)
    {
        $default_options = array(
            'charset'  => null,
            'driver'   => 'pdo_mysql',
            'dbname'   => null,
            'host'     => 'localhost',
            'user'     => 'root',
            'password' => null,
            'port'     => null,
        );
        
        $options = array_merge($default_options, $options);
        
        $config = new Configuration();
        
        $connection = DriverManager::getConnection($options, $config);
        
//        $app->setKey('dbal', $connection);
    }

}
