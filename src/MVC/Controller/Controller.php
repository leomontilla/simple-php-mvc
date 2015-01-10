<?php

/**
 * Controller
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */

namespace MVC;

use MVC\Server\HttpRequest,
    MVC\View,
    MVC\Errors\RuntimeException;

class Controller
{

    /**
     * Response of Controller
     * @access protected
     * @var array $_response
     */
    protected $_response;

    /**
     * Object of the View
     * @access protected
     * @var View
     */
    protected $view;

    /**
     * Create the object View
     */
    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Call a action of controller
     * @access public
     * @param MVC $mvc         Object of Application
     * @param HttpRequest $request Object of Request class
     * @param string $action   Action of Controller
     * @param string $fileView String of the view file
     * @return array
     */
    public function call(MVC $mvc, HttpRequest $request, $action, $fileView)
    {
        if (!method_exists($this, $action)) {
            RuntimeException::run("Method '$action' don´t exists.");
        }
        
        $this->view = $mvc->view();
        
        #Array vars
        $vars = call_user_func_array(array(&$this, $action), array($mvc, $request));

        if (is_null($vars) || $vars === false) {
            return false;
        }
        $this->_response = array();
        if (is_string($vars)) {
            $this->_response['body'] = $vars;
            return $this->_response;
        }

        if ($request->is("ajax")) {
            $this->_response['body'] = $this->render_json($vars);
        } else {
            $class = explode("\\", get_called_class());
            $classname = end($class);
            // Class without Controller
            $classname = str_replace('/(Controller)/i', '', $classname);
            $file = $classname . "/{$fileView}";
            $this->_response['body'] = $this->render_html($file, $vars);
        }

        return $this->_response;
    }

    /**
     * Function for Error 404: Not Found
     * @access public
     * @param Request $request
     * @return array
     */
    public function _404(Request $request)
    {
        $uri = $request->getRequestUri();
        return $this->view->render("404.html", array("uri", "uri"));
    }

    /**
     * Converts the supplied value to JSON.
     * @access public
     * @param mixed $value    The value to encode.
     * @return string
     */
    public function render_json($value)
    {
        $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
        return json_encode($value, $options);
    }

    /**
     * Renders a view.
     * @access public
     * @param string $file      The file to be rendered.
     * @param array $vars       The variables to be substituted in the view.
     * @return mixed
     */
    public function render_html($file, $vars = array())
    {
        return $this->view->render($file, $vars);
    }

    /**
     * Get the View object
     * @access public
     * @return View
     */
    public function view()
    {
        return $this->view;
    }

}
