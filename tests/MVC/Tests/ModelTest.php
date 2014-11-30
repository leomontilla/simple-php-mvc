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
        $sql = 'INSERT INTO estados (id_estados, nombre) VALUES (?, ?)';
        $params = array(25, 'Agregado');
        
        $filasAfectadas = $this->_model->insert($sql, $params);
        
        $this->assertInternalType('int', $filasAfectadas);
    }
    
    public function testUpdate()
    {
        $sql = 'UPDATE estados SET nombre = ? WHERE id_estados = ?';
        $params = array('Cambiado', 25);
        
        $filasAfectadas = $this->_model->update($sql, $params);
        
        $this->assertInternalType('int', $filasAfectadas);
    }
    
    public function testDelete()
    {
        $sql = 'DELETE FROM estados WHERE id_estados = ?';
        $params = array(25);
        
        $filasAfectadas = $this->_model->delete($sql, $params);
        
        $this->assertInternalType('int', $filasAfectadas);
    }

}
