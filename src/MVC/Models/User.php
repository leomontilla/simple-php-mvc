<?php 
namespace MVC\Models;

/**
 * Description of User class
 * @author name
 */
class User extends \MVC\Database\Functions_DB {

    public function __construct() {
        $path_config_file = dirname(dirname(dirname(__DIR__))) . "/config-database.php";
    	parent::__construct($path_config_file);
        $this->table = "users";
    }

}
	