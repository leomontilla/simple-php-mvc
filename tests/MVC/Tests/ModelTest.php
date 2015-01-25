<?php

/**
 * Prueba de Modelo
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\Tests;

use MVC\DataBase\PDO;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    
    protected $_model;
    
    protected $_pdo;

    protected function setUp()
    {
        $this->_pdo = new PDO('mysql:host=localhost;dbname=MPPD_DB;charset=UTF8', 'root', '');
        $this->_model = new Model($this->_pdo);
    }
    
    public function testQuery()
    {
        $pdos = $this->_model->query('SELECT * FROM estados');
        
        $this->assertInstanceOf('MVC\DataBase\PDOStatement', $pdos);
    }
    
    public function testInsert()
    {
        $data = array('id_estados' => 25, 'nombre' => 'Agregado');
        
        $filasAfectadas = $this->_model->insert($data);
        
        $this->assertInternalType('int', $filasAfectadas);
    }
    
    public function testUpdate()
    {
        $data = array('nombre' => 'Agregado');
        $criteria = array('id_estados' => 25);
        
        $filasAfectadas = $this->_model->update($data, $criteria);
        
        $this->assertInternalType('int', $filasAfectadas);
    }
    
    public function testDelete()
    {
        $criteria = array('id_estados' => 25);
        
        $filasAfectadas = $this->_model->delete($criteria);
        
        $this->assertInternalType('int', $filasAfectadas);
    }
    
    public function testSelectAll()
    {
        $fields = array('*');
        $criteria = array();
        $operators = array('=');
        $conditions = array('AND');
        
        $estados = $this->_model->select($fields, $criteria, $operators, $conditions);
        
        $this->assertInternalType('array', $estados);
    }
    
    public function testSelectEspecific()
    {
        $fields = array('id_estados', 'nombre');
        $criteria = array('id_estados' => 24);
        $operators = array('=');
        
        $estados = $this->_model->select($fields, $criteria, $operators);
        
        $this->assertInternalType('array', $estados);
    }
    
    public function testSelectAndOrCondition()
    {
        $fields = array('id_estados', 'nombre');
        $criteria = array('id_estados' => 2, 'nombre' => 'Aragua');
        $operators = array('!=', '!=');
        $conditions = array("AND");
        $operators2 = array('=', '=');
        $conditions2 = array('OR');
        
        $estados = $this->_model->select($fields, $criteria, $operators, $conditions);
        $estados2 = $this->_model->select($fields, $criteria, $operators2, $conditions2);
        
        $this->assertInternalType('array', $estados);
        $this->assertInternalType('array', $estados2);
    }

    public function testFindAll()
    {
        $estados = $this->_model->findAll();
        
        $this->assertInternalType('array', $estados);
    }
    
    public function testFindBy()
    {
        $estados = $this->_model->findBy('nombre', 'Aragua');
        
        $this->assertInternalType('array', $estados);
    }
}
