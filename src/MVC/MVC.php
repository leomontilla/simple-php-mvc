<?php

namespace MVC;

use MVC\Application\Container,
    MVC\Module\Module,
    MVC\Provider\Provider,
    MVC\Provider\ProviderInterface,
    MVC\Server\HttpRequest,
    MVC\Server\Response,
    MVC\Server\Route,
    MVC\View;

/**
 * Description of Class MVC (Model View Controller)
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 * @version 1.5
 */
class MVC implements MVCInterface
{

    /**
     * MVC Application Booted
     * 
     * @var boolean
     */
    protected $booted = false;
    
    /**
     * Container of the aplication
     * 
     * @access protected
     * @var Container $container
     */
    protected $container;
    
    /**
     * Static instance of MVC
     * 
     * @var MVC
     */
    static $instance;
    
    /**
     * Constructor
     * 
     * @access public
     * @param  array $userSettings Associative array of application settings
     */
    public function __construct(array $userSettings = array())
    {
        $settings = array_merge(self::getDefaultSettings(), $userSettings);
        $this->container = new Container($settings);
        
        # Init Modules, Providers and Routes
        $this->initModules()
             ->initProviders()
             ->initRoutes();
    }
    
    /**
     * Boots of the all providers of the application
     * 
     * @access public
     * @return void
     */
    public function boot()
    {
        if (!$this->booted) {
            foreach ($this->container->getProviders() as $provider) {
                $provider->boot($this);
            }

            $this->booted = true;
        }
    }
    
    /**
     * Configure MVC Settings
     *
     * This method defines application settings and acts as a setter and a getter.
     *
     * If only one argument is specified and that argument is a string, the value
     * of the setting identified by the first argument will be returned, or NULL if
     * that setting does not exist.
     *
     * If only one argument is specified and that argument is an associative array,
     * the array will be merged into the existing application settings.
     *
     * If two arguments are provided, the first argument is the name of the setting
     * to be created or updated, and the second argument is the setting value.
     * @access public
     * @param  string|array $name  If a string, the name of the setting to set or retrieve. Else an associated array of setting names and values
     * @param  mixed        $value If name is a string, the value of the setting identified by $name
     * @return mixed        The value of a setting if only one argument is a string
     */
    public function config($name, $value = null)
    {
        $c = $this->container->getSettings();

        if ($name === "templates_path") {
            $this->container->getView()->$name = $value;
        }

        if (is_array($name)) {
            if (true === $value) {
                $c = array_merge_recursive($c, $name);
            } else {
                $c = array_merge($c, $name);
            }
        } elseif (func_num_args() === 1) {
            return isset($c[$name]) ? $c[$name] : null;
        } else {
            $settings = $c;
            $settings[$name] = $value;
            $c = $settings;
        }
    }
 
    /**
     * Get Application Dir
     * 
     * @return string Application Dir
     */
    public function getAppDir()
    {
        if (null === $this->container->getAppDir()) {
            $r = new \ReflectionObject($this);
            $this->container->setAppDir(str_replace('\\', '/', dirname($r->getFileName())));
        }

        return $this->container->getAppDir();
    }
    
    /**
     * Get container
     * 
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get default application settings
     * 
     * @access public
     * @return array
     */
    public static function getDefaultSettings()
    {
        return array(
            "charset" => "UTF-8",
            "debug" => true,
            "error_writer" => true,
            "templates_path" => "./templates"
        );
    }
    
    /**
     * Get instance of MVC
     * 
     * @param array $userSettings
     * @return MVC
     */
    public static function getInstance(array $userSettings = array())
    {
        if (!self::$instance) {
            self::$instance = new self($userSettings);
        }
        
        return self::$instance;
    }
    
    /**
     * Get container key or container
     * 
     * @access public
     * @param string $key
     * @return \stdClass|mixed
     */
//    public function getKey($key = null)
//    {
//        $providers = $this->container->getProviders();
//        if ($key && isset($providers[$key])) {
//            return $providers[$key];
//        }
//    }
    
    /**
     * Get registered modules
     * 
     * @return array
     */
    public function getModules()
    {
        return $this->container->getModules();
    }
    
    /**
     * Get registered providers
     * 
     * @return Provider[] Registered providers
     */
    public function getProviders()
    {
        return $this->container->getProviders();
    }
    
    /**
     * Initialize Modules
     * 
     * @return MVC
     */
    final protected function initModules()
    {
        foreach ($this->setModules() as $module) {
            $this->registerModule($module);
        }
        return $this;
    }
    
    /**
     * Initialize Providers
     * 
     * @return MVC
     * @throws \LogicException
     */
    final protected function initProviders()
    {
        foreach ($this->setProviders() as $currentProvider) {
            if (!is_array($currentProvider) || count($currentProvider) !== 2) {
                throw new \LogicException(sprintf('Provider params invalids. Expected array(ProviderInterface provider, array options) or array(array options, ProviderInterface provider).'));
            }
            $currentProvider = array_values($currentProvider);
            if (is_object($currentProvider[0]) && is_array($currentProvider[1])) {
                $this->registerProvider($currentProvider[0], $currentProvider[1]);
            } elseif (is_array($currentProvider[0]) && is_object($currentProvider[1])) {
                $this->registerProvider($currentProvider[1], $currentProvider[0]);
            } else {
                throw new \LogicException(sprintf('Provider params invalids. Expected array(ProviderInterface provider, array options) or array(array options, ProviderInterface provider).'));
            }
        }
        return $this;
    }
    
    /**
     * Initialize Routes
     * 
     * @return MVC
     * @throws \LogicException
     */
    final protected function initRoutes()
    {
        $routes = $this->setRoutes();
        foreach ($routes as $currentRoutes) {
            foreach ($currentRoutes as $route) {
                $this->container->addRoute($route);
            }
        }
        
        return $this;
    }
    
    /**
     * Return if provider exists
     * 
     * @access public
     * @param string $key
     * @return boolean
     */
//    public function keyExists($key)
//    {
//        $providers = $this->container->getProviders();
//        if (isset($providers[$key])) {
//            return true;
//        } else {
//            return false;
//        }
//    }

    /**
     * Add Group routes
     * 
     * @access public
     * @return void
     */
    public function group()
    {
        $args = func_get_args();
        $route = array_shift($args);
        $callable = array_pop($args);
        if (is_string($route) && is_callable($callable)) {
            call_user_func($callable, $route);
        }
    }

    /**
     * Add AJAX route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function ajax($patternUri, $action, $name)
    {
        $route = new Route("ajax", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add HEAD route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function head($patternUri, $action, $name)
    {
        $route = new Route("head", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add GET route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function get($patternUri, $action, $name)
    {
        $route = new Route("get", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add OPTIONS route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function options($patternUri, $action, $name)
    {
        $route = new Route("options", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add POST route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function post($patternUri, $action, $name)
    {
        $route = new Route("post", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add PUI route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function put($patternUri, $action, $name)
    {
        $route = new Route("put", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Add DELETE route
     * 
     * @access public
     * @param string $patternUri
     * @param string|\callable $action
     * @paran string $name
     * @return Route
     */
    public function delete($patternUri, $action, $name)
    {
        $route = new Route("delete", $patternUri, $action, $name);
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Checks for request characteristics.
     * ajax, delete, flash, get, head, mobile, options, post, put, ssl
     * 
     * @access public
     * @param string $caracteristic   The request caracteristic
     * @return mixed
     */
    public function is($caracteristic)
    {
        return $this->container->getRequest()->is($caracteristic);
    }

    /**
     * Redirect
     *
     * This method immediately redirects to a new URL. By default,
     * this issues a 302 Found response; this is considered the default
     * generic redirect response. You may also specify another valid
     * 3xx status code if you want. This method will automatically set the
     * HTTP Location header for you using the URL parameter.
     * @access public
     * @param  string   $url        The destination URL
     * @param  int      $status     The HTTP redirect status code (optional)
     * @return void
     */
    public function redirect($url, $status = 302)
    {
        $this->container->getResponse()->redirect($url, $status);
    }

    /**
     * Register the modules
     * 
     * @param Module $module
     * @return MVC
     * @throws \LogicException
     */
    public function registerModule(Module $module)
    {
        $this->container->addModule($module);
        
        return $this;
    }
    
    /**
     * Register the providers
     * 
     * @access public
     * @param ProviderInterface $provider
     * @param array $options
     * @return MVC
     */
    public function registerProvider(ProviderInterface $provider, array $options = array())
    {
        $this->container->addProvider($provider);

        $provider->register($this, $options);
        
        return $this;
    }
    
    /**
     * Set container key and value
     * 
     * @access public
     * @param string $key
     * @param mixed $value
     */
//    public function setKey($key, $value)
//    {
//        if ($key && $value) {
//            $providers = $this->container->getProviders();
//            $providers[$key] = $value;
//        }
//    }
    
    /**
     * Set Modules to register
     * 
     * @return array
     */
    public function setModules()
    {
        return array();
    }
    
    /**
     * Set Providers to register
     * 
     * @return array
     */
    public function setProviders()
    {
        return array();
    }
    
    /**
     * Set Routes from JSON|PHP File
     * 
     * @return array
     */
    public function setRoutes()
    {
        $routesJsonFile = $this->getAppDir() . '/config/routes.json';
        $routesPhpFile = $this->getAppDir() . '/config/routes.php';
        $routes = array();

        if (file_exists($routesJsonFile)) {
            $routes[] = json_decode(file_get_contents($routesJsonFile), true);
        } elseif (file_exists($routesPhpFile)) {
            $routes[] = require_once $routesPhpFile;
        }

        foreach ($this->container->getModules() as $module) {
            $routes[] = $module->getModuleExtension()->loadRoutes();
        }

        return $routes;
    }
    
    /**
     * Share a clousure object or callback object
     * 
     * @access public
     * @param $callable
     * @return callable
     * @throws InvalidArgumentException
     */
    public static function share($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Callable is not a Closure or invokable object.');
        }
        
        return function ($c) use ($callable) {
            static $object;

            if (null === $object) {
                $object = $callable($c);
            }

            return $object;
        };
    }
    
    /**
     * Share a protected clousure object
     * 
     * @access public
     * @param  $callable
     * @return callable
     * @throws InvalidArgumentException
     */
    public static function protect($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Callable is not a Closure or invokable object.');
        }
        return function ($c) use ($callable) {
            return $callable;
        };
    }

    /**
     * Returns the URL for the name or route
     * 
     * @access public
     * @param string $name      Name of Route
     * @param boolean $relative If is true is a relative URL, else a absolute url
     * @return string
     */
    public function generateUrl($name, $relative = true)
    {
        $routes = $this->container->getRoutes();
        if ($relative) {
            return isset($routes[$name]) ? $routes[$name][1] : '';
        } else {
            $rootUri = $this->container->getRequest()->getRootUri();
            return isset($routes[$name]) ? $rootUri . $routes[$name][1] : '';
        }
    }

    /**
     * Not Found Handler
     *
     * @access public
     * @param  string|\callable $action Anything that returns true for is_callable()
     * @return Route
     */
    public function notFound($action = null)
    {
        $methods = array("get", "post", "put", "delete", "ajax", "options", "head", "mobile");
        $route = new Route($methods, '*', $action, 'notFound');
        $this->container->addRoute($route);
        return $route;
    }

    /**
     * Get the Request object
     * 
     * @access public
     * @return HttpRequest
     */
    public function request()
    {
        return $this->container->getRequest();
    }

    /**
     * Get the data of request
     * 
     * @access public
     * @return \stdClass
     */
    public function data($json = false)
    {
        return ($json) ? $this->container->getRequest()->data->JSON : $this->container->getRequest()->data;
    }

    /**
     * Get the query of request
     * 
     * @access public
     * @return \stdClass
     */
    public function query()
    {
        return $this->container->getRequest()->query;
    }

    /**
     * Get the Response object
     * 
     * @access public
     * @return Response
     */
    public function response()
    {
        return $this->container->getResponse();
    }

    /**
     * Get the View object
     * 
     * @access public
     * @return View
     */
    public function view()
    {
        return $this->container->getView();
    }

    /**
     * Render the template
     * 
     * @access public
     * @param string $template
     * @param array $data
     * @param int $status
     * @return void
     */
    public function render($template, $data = array(), $status = null)
    {
        if (!is_null($status) && headers_sent() === false) {
            header($this->container->getResponse()->_convert_status($status));
        }
        $this->container->getView()->display($template, $data);
    }

    /**
     * Run the aplication
     * 
     * @access public
     * @return void
     */
    public function run(HttpRequest $request = null)
    {
        if (!$this->container->getSetting('debug')) {
            error_reporting(0);
        } else {
            error_reporting(E_ALL);
        }
        
        if (!$request) {
            $request = $this->container->getRequest();
        }
        
        try {
            $parsed = $this->container->getRouter()->parse($request, $this->container->getRoutes());

            if ($parsed['found'] || $this->container->hasRoute('notFound')) {
                if (is_string($parsed['action'])) {
                    list($controller, $method) = explode('::', $parsed['action']);

                    $arguments = array($this, $request, $parsed['params']);

                    $controller = new $controller();

                    $response = call_user_func_array(array(&$controller, $method), $arguments);

                    if (is_array($response) && !isset($response['body'])) {
                        throw Error::run("Invalid response array. Expected array('body' => string, 'status' => int).");
                    } elseif (is_string($response)) {
                        $response = array('body' => $response);
                    }
                } elseif(is_callable($parsed['action'])) {
                    $this->container->getRequest()->params = $parsed['params'];

                    $response = call_user_func_array($parsed['action'], array_values($parsed['params']));
                } else {
                    $response = false;
                }
                if ($this->container->hasProvider('monolog')) {
                    //$this->container->providers['monolog']->addInfo($response, $parsed);
                }
                $this->container->getResponse()->render($response);
            } else {
                $this->defaultNotFound();
            }
        } catch (\Exception $e) {
            Error::run($e);
        }
    }

    /**
     * Generate diagnostic template markup.
     * This method accepts a title and body content to generate an HTML document layout.
     * 
     * @access public
     * @param  string   $title  The title of the HTML template
     * @param  string   $body   The body content of the HTML template
     * @return string
     */
    protected static function generateTemplateMarkup($title, $body)
    {
        return sprintf("<html><head><title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>", $title, $title, $body);
    }

    /**
     * Default Not Found handler
     * 
     * @access public
     * @return void
     */
    protected function defaultNotFound()
    {
        echo static::generateTemplateMarkup('404 Page Not Found', '<p>The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly. If all else fails, you can visit our home page at the link below.</p><a href="' . $this->container->getRequest()->getRootUri() . '">Visit the Home Page</a>');
    }

}
