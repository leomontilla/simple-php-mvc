<?php 

/**
 * Description of User class
 * @author name
 */

namespace MVC\Models;

use MVC\DataBase\Model,
    MVC\DataBase\PDO;

class User extends Model
{

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'usuario');
    }

}
	