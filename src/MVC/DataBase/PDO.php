<?php

/**
 * PDO 
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\DataBase;

class PDO
{
    /**
     * @var \PDO
     * @access protected
     */
    protected $_pdo;
    
    /**
     * @var int
     * @access public
     */
    public $numExecutes;
    
    /**
     * @var int
     * @access public
     */
    public $numStatements;
    
    /**
     * @var PDO
     */
    static $instance;
    
    /**
     * @param string $dsn
     * @param string $user
     * @param string $passwd
     * @param mixed $driverOptions
     */
    function __construct($dsn, $user = null, $passwd = null, $driverOptions = null)
    {
        $this->_pdo = new \PDO($dsn, $user, $passwd, $driverOptions);
        $this->numExecutes = 0;
        $this->numStatements = 0;
    }
    
    /**
     * @param string $method
     * @param array $arguments
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array(&$this->_pdo, $method), $arguments);
    }
    
    /**
     * @param string $dsn          URI of the driver
     * @param string $user         Connection user
     * @param string $passwd       Connection password
     * @param array $driverOptions Driver options
     * 
     * @return PDO
     */
    public static function __getInstance($dsn, $user = null, $passwd = null, array $driverOptions = array())
    {
        if (!self::$instance) {
            self::$instance = new self($dsn, $user, $passwd, $driverOptions);
        }
        return self::$instance;
    }

    /**
     * @param string $sql          Statement SQL
     * @param srray $driverOptions Driver options
     * 
     * @return PDOStatement
     */
    public function prepare($sql, array $driverOptions = array())
    {
        $this->numStatements++;

        $pdos = $this->_pdo->prepare($sql, $driverOptions);
        
        return new PDOStatement($this, $pdos);
    }
    
    /**
     * @param string $sql Statement SQL
     * 
     * @return PDOStatement
     */
    public function query($sql)
    {
        $this->numExecutes++;
        $this->numStatements++;
        
        $pdos = $this->_pdo->query($sql);
        
        return new PDOStatement($this, $pdos);
    }
    
    /**
     * @param string $sql Statement SQL
     * 
     * @return int Filas afectadas
     */
    public function exec($sql)
    {
        $this->numExecutes++;
           
        return $this->_pdo->exec($sql);
    }

}
