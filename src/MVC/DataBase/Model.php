<?php

/**
 * Modelo abstracto del que requeriran los demas modelos
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\DataBase;

abstract class Model
{

    protected $_columns;
    
    protected $_table;
    
    protected $_pdo;
    
    public static $instance;
    
    public function __construct(PDO $pdo, $table)
    {
        $this->_pdo = $pdo;
        $this->_table = $table;
    }

    /**
     * @param string $table
     * 
     * @return Model
     */
    public static function getInstance($table)
    {
        if (!self::$instance) {
            self::$instance = new self($table);
        }
        return self::$instance;
    }
    
    /**
     * @param string $sql
     * 
     * @return PDOStatement
     */
    protected function query($sql)
    {
        return $this->_pdo->prepare($sql);
    }
    
    /**
     * @param string $sql
     * @param array $params
     * 
     * @return int 
     */
    protected function delete($sql, array $params = array())
    {
        $pdos = $this->_pdo->prepare($sql);
        
        $pdos->execute($params);
        
        return $pdos->statement()->rowCount();
    }

    /**
     * @param string $sql
     * @param array $params
     * 
     * @return int
     */
    protected function insert($sql, array $params = array())
    {
        $pdos = $this->_pdo->prepare($sql);
        
        $pdos->execute($params);
        
        return $pdos->statement()->rowCount();
    }
    
    /**
     * @param string $sql
     * @param array $params
     * 
     * @return array
     */
    protected function select($sql, array $params = array())
    {
        $pdos = $this->_pdo->query($sql);
        
        return $pdos->fetchAll();
    }
    
    /**
     * @param string $sql
     * @param array $params
     * 
     * @return int 
     */
    protected function update($sql, array $params = array())
    {
        $pdos = $this->_pdo->prepare($sql);
        
        $pdos->execute($params);
        
        return $pdos->statement()->rowCount();
    }

}
