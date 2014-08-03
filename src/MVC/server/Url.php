<?php

namespace MVC\server;

/**
 * Description of Url
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\server
 */
class Url {

    /**
     * Generate url
     *
     * @param string $uri
     * @return string
     */
    static function urlTo($uri) {
        print URL_BASE . $uri;
    }
    
    /**
     * Print the url for css files
     *
     * @param string $file
     * @return void
     */
    static function urlCss($file) {
        print URL_BASE . "/bootstrap/css/$file";
    }

    /**
     * Print the url for js files
     *
     * @param string $file
     * @return string
     */
    static function urlJs($file) {
        print URL_BASE . "/bootstrap/js/$file";
    }
    
    /**
     * Print the url base
     *
     * @return void
     */
    static function getUrl(){
        print URL_BASE;
    }
    
    /**
     * Return the actual URI
     *
     * @return string
     */
    static function getUriActual() {
        return $_SERVER['REQUEST_URI'];
    }

}