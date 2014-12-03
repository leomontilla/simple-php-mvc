<?php

/**
 * Command Application for create files of the application
 * 
 * @author Ramon Serrano
 * @package MVC\Command
 */

namespace MVC\Command;

use MVC\Command\Controller,
    MVC\Command\Model,
    MVC\Command\Test;

class Command implements Controller, Model, Test {

    /**
     * Model name 
     * @var string
     * @access protected 
     * @name $model_name
     */
    protected $model_name;

    /**
     * Root Path
     * @var string
     * @access protected
     * @name $root 
     */
    protected $root;

    /**
     * Array Settings
     * @var array
     * @access protected
     * @name $settings
     */
    protected $settings;

    /**
     * Unit test
     * @var string
     * @access protected 
     * @name $unit_test
     */
    protected $unit_test;

    /**
     * Constructor
     * @access public
     * @param string $root
     * @param array $userSettings
     */
    public function __construct($root, array $userSettings = array()) {
        $this->root = $root;
        $this->settings = array_merge(static::getDefaultSettings(), $userSettings);
    }

    /**
     * Validate the settings
     * @access public
     * @return void
     */
    public function validateSettings() {
        if (!file_exists("$this->root/{$this->settings['app_path']}")) {
            print "\nError\n";
            exit("Don't exist the folder: $this->root/{$this->settings['app_path']}");
        }
        foreach ($this->settings as $key => $value) {
            if ($key === "views_path" || $key === "models_path" || $key === "controllers_path") {
                if (!file_exists("$this->root/{$this->settings['app_path']}/$value")) {
                    print "\nError\n";
                    exit("Don't exist the folder: $this->root/{$this->settings['app_path']}/$value");
                }
            }
        }
    }

    /**
     * Get default application settings
     * @access public
     * @return array
     */
    public static function getDefaultSettings() {
        return array(
            "app_path" => "./lib/MVC",
            "controllers_path" => "./Controllers",
            "models_path" => "./Models",
            "views_path" => "./Views",
            "namespace_controllers" => "MVC\\Controllers",
            "namespace_models" => "MVC\\Models"
        );
    }

    /**
     * Singular settings
     * @access public
     * @param string $name
     * @param string $value
     * @return void
     */
    public function config($name, $value = "") {
        if (!is_null($name) && is_null($value)) {
            return $this->settings[$name];
        }
        if (!is_null($name) && !is_null($value)) {
            $this->settings[$name] = $value;
        }
    }

    /**
     * Build model: auxiliar function
     * @access protected
     * @return void
     */
    protected function build_module() {
        $this->buildModel();
        $this->buildController();
    }

    /**
     * Build de Config DB File in the root path
     * @access public
     * @return void
     */
    public function buildConfigDatabaseFile() {
        $this->makeFile("config-database.php", "$this->root");
        $file = fopen("$this->root/config-database.php", "w");
        fwrite($file, '<?php 
$database = array(
    /*
      |--------------------------------------------------------------------------
      | Server localhost by default
      |--------------------------------------------------------------------------
     */
    "host" => "localhost",
    /*
      |--------------------------------------------------------------------------
      | Port 5432 by default
      |--------------------------------------------------------------------------
     */
    "port" => 3306,
    /*
      |--------------------------------------------------------------------------
      | Database name NULL by default
      |--------------------------------------------------------------------------
     */
    "db_name" => "database_name",
    /*
      |--------------------------------------------------------------------------
      | Display SQL Format false | HTML | PHP
      |--------------------------------------------------------------------------
     */
    "display_sql" => false,
    /*
      |--------------------------------------------------------------------------
      | Username to connection root by default
      |--------------------------------------------------------------------------
     */
    "user" => "root",
    /*
      |--------------------------------------------------------------------------
      | Username´s password NULL by default
      |--------------------------------------------------------------------------
     */
    "password" => "",
    /*
      |--------------------------------------------------------------------------
      | Socket null by default
      |--------------------------------------------------------------------------
     */
    "socket" => NULL,
);
       ');
        fclose($file);
    }

    /**
     * Build a controller file
     * @access public
     * @return boolean
     */
    public function buildController() {
        print "\nNombre para el controlador: [new] ";
        $name_default = "new";
        $name = $this->get();
        if (!empty($name)) {
            $nameController = $name;
        } else {
            $nameController = $name_default;
        }

        #View folder name
        $views_path = "$this->root/{$this->settings['app_path']}/{$this->settings['views_path']}";
        $view_path = "$this->root/{$this->settings['app_path']}/{$this->settings['views_path']}/$name" . "Controller";

        #Make Controller file
        $name = $this->toCammelCase($name) . "Controller.php";
        $this->makeFile($name, "$this->root/{$this->settings['app_path']}/{$this->settings['controllers_path']}");
        print "\nControlador creado.\n";

        #Make dir folder views
        if (!file_exists($views_path)) {
            mkdir($views_path);
        }        
        $this->makeFile("index.html", $view_path);
        print "\nVista creada.\n";
        return true;
    }

    /**
     * Build a model file
     * @access public
     * @return boolean
     */
    public function buildModel() {
        print "\nNombre para el modelo: [New] ";
        $name_default = "New.php";
        $name = $this->get();
        if (!empty($name)) {
            $name = $this->toCammelCase($name) . ".php";
            $models_path = "$this->root/{$this->settings['app_path']}/{$this->settings['models_path']}";
            $this->makeFile($name, $models_path);
        } else {
            $this->makeFile($name_default, "$this->root/{$this->settings['app_path']}/{$this->settings['models_path']}");
        }
        print "\nModelo creado.\n";
        return true;
    }

    /**
     * Build a unit test file
     * @access public
     * @return boolean
     */
    public function buildUnitTest() {
        print "\nNombre de la clase para la prueba unitaria: New ";
        $name_default = "NewTest.php";
        $unit_test = $this->get();
        if (!empty($unit_test)) {
            $unit_test = $this->toCammelCase($unit_test) . "Test.php";
            print "\nPrueba unitaria creada: ";
            $this->makeFile($unit_test, "$this->root/tests");
        } else {
            print "\nPrueba unitaria creada: ";
            $this->makeFile($name_default, "$this->root/tests");
        }
        return true;
    }

    /**
     * Getter of keyboard
     * @access public
     * @return string
     */
    public function get() {
        return trim(fgets(STDIN));
    }

    /**
     * Puts the text help
     * @access protected
     * @return void
     */
    protected function help() {
        print "
\nUsage: php Command [option]

 build_controller       Build a new Controller with a View file.
 build_module           Build a Module with Model, Controller and View.
 build_model            Build a new Model.
 build_test             Build a unit test.
 make_config_db         Make the config file for the connection with the database

 --help -help -h 	    	Help text
 		\r\n";
    }

    /**
     * Write the content of the controller file
     * @access public
     * @param string $nameFile
     * @param string $pathFile
     * @return void
     */
    public function makeController($nameFile, $pathFile) {
        $nameFile = explode(".", $nameFile);
        fwrite($pathFile, '<?php 

namespace ' . $this->settings['namespace_controllers'] . ';

/**
* Description of ' . $nameFile[0] . '
*/
class ' . $nameFile[0] . ' extends \MVC\Controller
{

	public function index( $mvc )
	{
		$m = new \MVC\models\\' . $this->model_name[0] . ';
		$values = $m->all();
		return array("key" => $values);
	}

}
	');
    }

    /**
     * Write the content of the model file
     * @access public
     * @param string $nameFile
     * @param string $pathFile
     * @return void
     */
    public function makeModel($nameFile, $pathFile) {
        $this->model_name = explode(".", $nameFile);
        fwrite($pathFile, '<?php 
namespace ' . $this->settings['namespace_models'] . ';

require dirname(__DIR__) . "/Database/DB.php";
require dirname(__DIR__) . "/Database/Functions_DB.php";
require dirname(__DIR__) . "/Errors/Exception.php";

/**
 * Description of ' . $this->model_name[0] . ' class
 * @author name
 */
class ' . $this->model_name[0] . ' extends \MVC\Database\Functions_DB {

    public function __construct() {
        $path_config_file = dirname(dirname(dirname(__DIR__))) . "/config-database.php";
    	  parent::__construct($path_config_file);
        $this->table = "' . lcfirst($this->model_name[0]) . 's";
    }

}
	');
    }

    /**
     * Write the content of the unit test file
     * @access public
     * @param string $nameFile
     * @param string $pathFile
     * @return void
     */
    public function makeUnitTest($nameFile, $pathFile) {
        $nameFile = explode(".", $nameFile);
        fwrite($pathFile, '<?php 

/**
* Description of ' . $nameFile[0] . '
* @author name
*/
class ' . $nameFile[0] . ' extends \PHPUnit_Framework_TestCase
{

	public function test(  )
	{
		print "Start UnitTest";
	}

}
	');
    }

    /**
     * Create the file
     * @access protected
     * @param string $nameFile
     * @param string $path
     * @return void
     * @throws Exception
     */
    protected function makeFile($nameFile, $path = null) {
        if (!is_null($path)) {
            if(!file_exists($path)) {
                print "\nNo existe la carpeta $path.\n";
                if (mkdir($path)) {
                    print "\nCarpeta $path creada con exito.";
                }
            } else if (file_exists($path . "/" . $nameFile)) {
                die ("\nEl archivo: $path/$nameFile ya existe.\n");
            } else {
                $file = fopen($path . "/" . $nameFile, "w");
                $array_file = explode(".", $nameFile);
                if (end($array_file) == "html") {
                    fwrite($file, "Ejemplo de vista $nameFile" . ' <?php print_r ($key) ?>');
                } elseif (end($array_file) == "php") {
                    $path_array = explode('/', $path);

                    foreach ($path_array as $value) {

                        if ($value == "controllers") {
                            $this->makeController($nameFile, $file);
                            break;
                        }
                        if ($value == "models") {
                            $this->makeModel($nameFile, $file);
                            break;
                        }
                        if ($value == "tests") {
                            $this->makeUnitTest($nameFile, $file);
                            break;
                        }
                    }
                }
                fclose($file);
                print " $path/$nameFile\n";
            }
        } else {
            throw new Exception("Debe indicar el directorio del archivo");
        }
    }

    /**
     * Run the Command Application.
     * Gets the args and runs the functions to the commands strings
     * @access public
     * @param array $arguments
     * @return void
     */
    public function run($arguments) {
        print "MVC PHP 1.0 by Ramon Serrano.\n";
        if (!isset($arguments[1])) {
            $this->help();
        } elseif ($arguments[1] == "--help" || $arguments[1] == "-help" || $arguments[1] == "-h") {
            $this->help();
        } else {
            switch ($arguments[1]) {
                case "build_controller":
                    $this->buildController();
                    break;
                case "build_model":
                    $this->buildModel();
                    break;
                case "build_module":
                    $this->build_module();
                    break;
                case "build_test":
                    $this->buildUnitTest();
                    break;
                case "make_config_db":
                    $this->buildConfigDatabaseFile();
                    break;
                default :
                    $this->help();
            }
        }
    }

    /**
     * Returns the string cammel case
     * @access public
     * @param string $string
     * @return string
     */
    protected function toCammelCase($string = null) {
        $string[0] = strtoupper($string[0]);
        return $string;
    }

}
