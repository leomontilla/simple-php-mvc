<?php

namespace MVC\errors;

/**
 * Description of Exception
 * @author Ramon Serrano
 * @package MVC\errors
 */
class RuntimeException {

    /**
     * Function to Exceptions
     * 
     * @param string $message
     * @throws \Exception
     */
    public static function run($message) {
        print '<meta charset="UTF8">';
        print "<h1>MVC Framework</h1>";
        print "<p><b>RuntimeException</b></p>";
        print "<p><b>Error:</b> $message</p>";
        print "<pre>";        
        throw new \RuntimeException($message);
    }

}

?>