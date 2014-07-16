<?php

namespace MVC;

use MVC\View,
    MVC\errors\RuntimeException,
    \MVC\server\Url;

/**
 * Description of AppController
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */
class Controller {

    /**
     * Response of Controller
     * @var \stdClass $_response
     */
    protected $_response;

    /**
     * @var \MVC\server\View
     */
    protected $view;

    /**
     * Create the object View
     */
    public function __construct() {
        $this->view = new View();
    }

    /**
     * Call a action of controller
     * @param string $action                  Action of Controller
     * @param \MVC\server\Request $request    Object of Request class
     * @param string $file_view               String of the view file
     * @return array
     */
    public function call($action, $request, $file_view) {
        if (!method_exists($this, "$action")) {
            RuntimeException::run("Method '$action' don´t exists.");
        }

        #Array vars
        $compact_vars = $this->$action($request);        

        if (is_null($compact_vars) || $compact_vars === false) {
            return false;
        }
        $this->_response = new \stdClass;
        if (!is_array($compact_vars)) {
            $this->_response->body = $compact_vars;
            return $this->_response;
        }

        if ($request->is("ajax")) {
            $this->_response->body = $this->render_json($compact_vars);
        } else {
            $class = explode("\\", get_called_class());
            $classname = end($class);
            $file = lcfirst($classname) . "/{$file_view}";
            $this->_response->body = $this->render_html($file, $compact_vars);
        }

        return $this->_response;
    }

    /**
     * 
     * Function for Error 404: Not Found
     * 
     * @param \MVC\server\Request $request
     * @return array
     */
    public function _404($request) {
        $url = Url::getUrl();
        $uri = $request->url;        
        return $this->view->render("404.html", compact("url", "uri"));
    }

    /**
     *  Converts the supplied value to JSON.
     *
     *  @param mixed $value    The value to encode.
     *  @return string
     */
    public function render_json($value) {
        $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
        return json_encode($value, $options);
    }

    /**
     *  Renders a view.
     *
     *  @param string $file      The file to be rendered.
     *  @param array $vars       The variables to be substituted in the view.
     *  @return mixed
     */
    public function render_html($file, $vars = array()) {
        return $this->view->render($file, $vars);
    }

    /**
     * 
     * Example of controller function or method
     * 
     * @param \MVC\server\Request $request
     * @return array
     */
    public function testCall($request) {
        $array1 = array("valor1", "valor2");
        $array2 = array("valor1", "valor2");
        return compact("array1", "array2");
    }
    
    /**
     * Get the View object
     * @return \MVC\View
     */
    public function view() {
        return $this->view;
    }

}
