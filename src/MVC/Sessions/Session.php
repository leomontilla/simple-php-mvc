<?php
/**
 * Description of Session
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */

namespace MVC\Sessions;

class Session 
{
    
    /**
     * Instance of Session
     * @access public
     * @var Session
     */
    public static $instance;
    
    /**
     * Construct of the class
     */
    public function __construct()
    {
        $this->__init();
    }
    
    /**
     * Gets the instance of Session
     * @access public
     * @return Session
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Start sessions vars
     * @access public
     * @return void
     */
    public static function __init() {
        session_start();
    }
    
    /**
     * Set var session
     * @access public
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function setSession($name, $value) {
        if (!isset($_SESSION[$name])) {
            $_SESSION[$name] = $value;
        } else {
            return false;
        }
    }

    /**
     * Get the value of Session key
     * @access public
     * @param $name
     * @return bool|mixed
     */
    public  function getSession($name) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    /**
     * Sessions destroy
     * @access public
     * @return void
     */
    public static function __destroy() {
        session_destroy();
    }

}
