<?php

/**
 * PDOStatement extendido
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\DataBase;

class PDOStatement
{

    /**
     * @var PDO
     */
    protected $_pdo;
    
    /**
     * @var \PDOStatement
     */
    protected $_pdos;
    
    /**
     * @param PDO $pdo
     * @param \PDOStatement $pdos
     */
    function __construct(PDO $pdo, \PDOStatement $pdos)
    {
        $this->_pdo = $pdo;
        $this->_pdos = $pdos;
    }
    
    /**
     * @param string $method
     * @param array $arguments
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array(&$this->_pdos, $method), $arguments);
    }
    
    /**
     * @param string $column
     * @param mixed $param
     * @param string $type
     */
    public function bindColumn($column, &$param, $type = null)
    {
        if ($type === null) :
            $this->_pdos->bindColumn($column, $param);
        else :
            $this->_pdos->bindColumn($column, $param, $type);
        endif;
    }
    
    /**
     * @param string $column
     * @param mixed $param
     * @param string $type
     */
    public function bindParam($column, &$param, $type = null)
    {
        if ($type === null) :
            $this->_pdos->bindParam($column, $param);
        else :
            $this->_pdos->bindParam($column, $param, $type);
        endif;
    }
    
    /**
     * @param array $params Params of the Statement SQL
     * 
     * @return boolean
     */
    public function execute(array $params = array())
    {
        $this->_pdo->numExecutes++;
        return $this->_pdos->execute($params);
    }
    
    /**
     * @return \stdClass
     */
    public function fetch()
    {
        return $this->_pdos->fetch(\PDO::FETCH_CLASS);
    }
    
    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->_pdos->fetchAll(\PDO::FETCH_CLASS);
    }

    /**
     * @param $property
     * 
     * @return mixed
     */
    public function __get($property)
    {
        return $this->_pdos->$property;
    }

    /**
     * @return \PDOStatement
     */
    public function statement()
    {
        return $this->_pdos;
    }

}
