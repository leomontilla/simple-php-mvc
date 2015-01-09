<?php

/**
 * Twig Framework Provider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\Providers
 */

namespace MVC\Tests\Provider;

use MVC\MVC,
    MVC\Provider\Provider;

class TwigProvider extends Provider
{
    /**
     * Bootstrap of the Provider
     * @access public
     * @param MVC $app
     * @return void
     */
    public function boot(MVC $app) { }

    /**
     * Register the properties of the Twig Framework Provider
     * @access public
     * @param MVC $app
     * @param array $options
     * @return void
     */
    public function register(MVC $app, array $options)
    {
        $defaultOptions = array(
//            'charset'          => $app->getKey('charset'),
//            'debug'            => $app->getKey('debug'),
//            'strict_variables' => $app->getKey('debug'),
//            'templates_path'   => $app->getKey('templates_path')
        );
        
        $options = array_merge($defaultOptions, $options);
        
//        $app->setKey('twig.loader.filesystem', new \Twig_Loader_Filesystem($options['path']));
//        $app->setKey('twig.loader.array', new \Twig_Loader_Array($options['templates_path']));
        
//        $app->setKey('twig.loader', new \Twig_Loader_Chain(array(
//            $app->getKey('twig.loader.array'),
//            $app->getKey('twig.loader.filesystem')
//        )));
        
//        $twig = new \Twig_Environment($app->getKey('twig.loader'), $options);
//        $twig->addGlobal('app', $app);
//        
//        if ($options['debug']) {
//            $twig->addExtension(new \Twig_Extension_Debug());
//        }
        
//        $app->setKey('twig', $twig);
    }

}
