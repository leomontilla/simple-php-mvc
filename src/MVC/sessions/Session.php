<?php

namespace MVC\sessions;

/**
 * Description of Session
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\sessions
 */
class Session {

    /**
     * Start sessions vars
     * @return void
     */
    static function __init() {
        session_start();
    }
    /**
     * Set var session
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    static function setSession($name, $value) {
        if (!isset($_SESSION[$name])) {
            $_SESSION[$name] = $value;
        } else {
            return false;
        }
    }

    /**
     * Get the session var
     * @param $name
     * @return bool|mixed
     */
    static function getSession($name) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    /**
     * Sessions destroy
     * @return void
     */
    static function __destroy() {
        session_destroy();
    }

}
