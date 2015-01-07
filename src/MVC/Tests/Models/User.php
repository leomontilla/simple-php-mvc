<?php 

/**
 * Example of User Model
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\Models
 */

namespace MVC\Tests\Models;

use MVC\DataBase\Model,
    MVC\DataBase\PDO;

class User extends Model
{

    /**
     * Construct of the class
     * @access public
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'usuario');
    }

}
	