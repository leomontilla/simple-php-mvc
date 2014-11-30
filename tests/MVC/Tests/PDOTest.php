<?php

/**
 * Prueba de PDOTest
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\Tests;

use MVC\Database\PDO,
    MVC\Database\PDOStatement;

class PDOTest extends \PHPUnit_Framework_TestCase
{
    
    protected $_pdo;

    protected function setUp()
    {
        $this->_pdo = new PDO('mysql:host=localhost;dbname=MPPD_DB;charset=UTF8', 'root', '');
    }
    
    public function testQuery()
    {
        $pdos = $this->_pdo->query('SELECT * FROM estados');
        
        $this->assertInstanceOf('MVC\Database\PDOStatement', $pdos);
    }
    
    public function testPDOStatement()
    {
        return $this->_pdo->prepare('SELECT * FROM estados');
    }
    
    /**
     * @depends testPDOStatement
     */
    public function testPrepare(PDOStatement $pdos)
    {   
        $this->assertInstanceOf('MVC\Database\PDOStatement', $pdos);
    }
    
    public function testExec()
    {
        $result = $this->_pdo->exec('SELECT * FROM estados');
        
        $this->assertInternalType('int', $result);
    }
}
