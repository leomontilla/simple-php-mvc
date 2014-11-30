<?php

/**
 * Model Example
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\Tests;

use MVC\DataBase\Model as AbstractModel,
    MVC\DataBase\PDO;

class Model extends AbstractModel
{
    
    public $id_estados;
    
    public $nombre;

    function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'estados');
    }
    
    function query($sql)
    {
        return parent::query($sql);
    }
    
    function delete($sql, array $params = array()) {
        return parent::delete($sql, $params);
    }
    
    function insert($sql, array $params = array()) {
        return parent::insert($sql, $params);
    }
    
    function select($sql, array $params = array())
    {
        return parent::select($sql, $params);
    }
    
    function update($sql, array $params = array()) {
        return parent::update($sql, $params);
    }
}
