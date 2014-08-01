<?php 
namespace MVC\models;

require dirname(__DIR__) . "/database/DB.php";
require dirname(__DIR__) . "/database/Functions_DB.php";
require dirname(__DIR__) . "/errors/Exception.php";

/**
 * Description of User class
 * @author name
 */
class User extends \MVC\database\Functions_DB {

    public function __construct() {
        $path_config_file = dirname(dirname(dirname(__DIR__))) . "/config-database.php";
    	  parent::__construct($path_config_file);
        $this->table = "users";
    }

}
	