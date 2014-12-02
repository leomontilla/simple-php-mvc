<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DoctrineDBALProvider
 *
 * @author smartapps
 */

namespace MVC\Providers;

use Doctrine\DBAL\Configuration,
    Doctrine\DBAL\DriverManager,
    MVC\MVC,
    MVC\ProviderInterface;

class DoctrineDBALProvider implements ProviderInterface
{
    public function boot(MVC $app) {
        
    }

    public function register(MVC $app, array $options) {
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
        
        $app->setKey('dbal', $connection);
    }

}
