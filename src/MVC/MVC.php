<?php

namespace MVC;

use MVC\Controller,
    MVC\Server\Router,
    MVC\Server\Request,
    MVC\Server\Response,
    MVC\View;

/**
 * Description of Class MVC (Model View Controller)
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 * @version 1.0
 */
class MVC
{

    /**
     * Container of the aplication
     * @var \stdClass $container
     */
    protected $container;

    /**
     * Constructor
     * @param  array $userSettings Associative array of application settings
     */
    public function __construct(array $userSettings = array())
    {
        $this->container = new \stdClass();
        $this->container->settings = array_merge(static::getDefaultSettings(), $userSettings);

        $this->container->request = new Request();
        $this->container->response = new Response();
        $this->container->router = new Router();
        $this->container->view = new View();
        $this->container->view->templatesPath = $this->container->settings['templates_path'];
        $this->container->controller = new Controller();
        $this->container->routes = array();
        
        # Providers
        $this->container->providers[] = array();
    }

    /**
     * Get default application settings
     * @return array
     */
    public static function getDefaultSettings()
    {
        return array(
            "debug" => true,
            "error_writer" => true,
            "templates_path" => "./templates"
        );
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
     *
     * @param  string|array $name  If a string, the name of the setting to set or retrieve. Else an associated array of setting names and values
     * @param  mixed        $value If name is a string, the value of the setting identified by $name
     * @return mixed        The value of a setting if only one argument is a string
     */
    public function config($name, $value = null)
    {
        $c = $this->container->settings;

        if ($name === "templates_path" || $name === "root_path") {
            $this->container->view->$name = $value;
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
     * Get container key or container
     * 
     * @param string $key
     * @return object
     */
    public function getKey($key = null)
    {
        if ($key && isset($this->container->providers[$key])) {
            return $this->container->providers[$key];
        }
        return $this->container;
    }
    
    /**
     * Set container key and value
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setKey($key, $value)
    {
        if ($key && $value) {
            $this->container->providers[$key] = $value;
        }
    }
    
    /**
     * Return if provider exists
     * 
     * @param string $key
     * @return boolean
     */
    public function keyExists($key)
    {
        if (isset($this->container->providers[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add Group routes
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
     * @param string $pattern
     * @param callable $callable
     * 
     * @return array
     */
    public function ajax($pattern, callable $callable)
    {
        $route = array("AJAX", $pattern, $callable);
        $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add HEAD route
     * 
     * @param string $pattern
     * @param callable $callable
     * 
     * @return array
     */
    public function head($pattern, callable $callable)
    {
        $route = array("HEAD", $pattern, $callable);
        $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add GET route
     * 
     * @param string $pattern
     * @param callable $callable
     * @param string $name
     * 
     * @return array
     */
    public function get($pattern, callable $callable, $name = null)
    {
        $route = array("GET", $pattern, $callable);
        if (is_string($name) && $name != '') $this->container->routes[$name] = $route;
        else $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add OPTIONS route
     * 
     * @param string $pattern
     * @param callable $callable
     * @param string $name
     * 
     * @return array
     */
    public function options($pattern, callable $callable, $name = null)
    {
        $route = array("OPTIONS", $pattern, $callable);
        if (is_string($name) && $name != '') $this->container->routes[$name] = $route;
        else $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add POST route
     * 
     * @param string $pattern
     * @param callable $callable
     * @param string $name
     * 
     * @return array
     */
    public function post($pattern, callable $callable, $name = null)
    {
        $route = array("POST", $pattern, $callable);
        if (is_string($name) && $name != '') $this->container->routes[$name] = $route;
        else $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add PUT route
     * 
     * @param string $pattern
     * @param callable $callable
     * @param string $name
     * 
     * @return array
     */
    public function put($pattern, callable $callable, $name = null)
    {
        $route = array("PUT", $pattern, $callable);
        if (is_string($name) && $name != '') $this->container->routes[$name] = $route;
        else $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Add DELETE route
     * 
     * @param string $pattern
     * @param callable $callable
     * @param string $name
     * 
     * @return array
     */
    public function delete($pattern, callable $callable, $name = null)
    {
        $route = array("DELETE", $pattern, $callable);
        if (is_string($name) && $name != '') $this->container->routes[$name] = $route;
        else $this->container->routes[] = $route;
        return $route;
    }

    /**
     * Checks for request characteristics.
     * ajax, delete, flash, get, head, mobile, options, post, put, ssl
     * @param string $caracteristic   The request caracteristic
     * @return mixed
     */
    public function is($caracteristic)
    {
        return $this->container->request->is($caracteristic);
    }

    /**
     * Redirect
     *
     * This method immediately redirects to a new URL. By default,
     * this issues a 302 Found response; this is considered the default
     * generic redirect response. You may also specify another valid
     * 3xx status code if you want. This method will automatically set the
     * HTTP Location header for you using the URL parameter.
     *
     * @param  string   $url        The destination URL
     * @param  int      $status     The HTTP redirect status code (optional)
     */
    public function redirect($url, $status = 302)
    {
        $this->container->response->redirect($url, $status);
    }
    
    /**
     * Register the providers
     * 
     * @param ProviderInterface $provider
     * @param array $options
     * @return MVC
     */
    public function registerProvider(ProviderInterface $provider, array $options = array())
    {
        $this->container->providers[] = $provider;
        
        $provider->register($this, $options);
        
        return $this;
    }
    
    /**
     * Boots of the all providers of the application
     */
    public function boot()
    {
        if (!$this->container->booted) {
            foreach ($this->container->providers as $provider) {
                $provider->boot($this);
            }

            $this->container->booted = true;
        }
    }
    
    /**
     * Share a clousure object or callback object
     * 
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
     * @param string $name      Name of Route
     * @param boolean $relative If is true is a relative URL, else a absolute url
     *
     * @return string
     */
    public function generateUrl($name, $relative = true)
    {
        if ($relative)
            return isset($this->container->routes[$name]) ? $this->container->routes[$name][1] : '';
        else
            return '';
    }

    /**
     * Not Found Handler
     *
     * This method defines or invokes the application-wide Not Found handler.
     * There are two contexts in which this method may be invoked:
     *
     * 1. When declaring the handler:
     *
     * If the $callable parameter is not null and is callable, this
     * method will register the callable to be invoked when no
     * routes match the current HTTP request. It WILL NOT invoke the callable.
     *
     * 2. When invoking the handler:
     *
     * If the $callable parameter is null, Slim assumes you want
     * to invoke an already-registered handler. If the handler has been
     * registered and is callable, it is invoked and sends a 404 HTTP Response
     * whose body is the output of the Not Found handler.
     *
     * @param  mixed $callable Anything that returns true for is_callable()
     * @return void
     */
    public function notFound($callable = null)
    {
        $args = func_get_args();
        $methods = array("GET", "POST", "PUT", "DELETE", "AJAX", "OPTIONS", "HEAD", "MOBILE");
        $this->container->routes['notFound'] = array($methods, "*", $args[0]);
    }

    /**
     * Get the Request object
     * @return Request
     */
    public function request()
    {
        return $this->container->request;
    }

    /**
     * Get the data of request
     * @return \stdClass
     */
    public function data()
    {
        return $this->container->request->data;
    }

    /**
     * Get the query of request
     * @return \stdClass
     */
    public function query()
    {
        return $this->container->request->query;
    }

    /**
     * Get the Response object
     * @return Response
     */
    public function response()
    {
        return $this->container->response;
    }

    /**
     * Get the View object
     * @return View
     */
    public function view()
    {
        return $this->container->view;
    }

    /**
     * Render the template
     * @param string $template
     * @param array $data
     * @param int $status
     * 
     * @return void
     */
    public function render($template, $data = array(), $status = null)
    {
        if (!is_null($status) && headers_sent() === false) {
            header($this->container->response->_convert_status($status));
        }
        $this->container->view->display($template, $data);
    }

    /**
     * Run the aplication
     * @return void
     */
    public function run()
    {

        if ($this->container->settings['debug'] === true) {
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }

        $parsed = $this->container->router->parse($this->container->request->url, $this->container->request->request_method, $this->container->routes);

        if ($parsed['found'] || isset($this->container->routes['notFound'])) {
            if (is_string($parsed['callback'])) {
                list($controller, $method) = explode('::', $parsed['callback']);
                
                $arguments = array($this, $this->container->request) + $parsed['params'];
                
                $controller = new $controller();
                
                $response = call_user_func_array(array(&$controller, $method), $arguments);
                
                if (is_array($response) && !isset($response['body'])) {
                    throw Errors\RuntimeException::run("Invalid response array. Expected array('body' => string, 'status' => int).");
                } elseif (is_string($response)) {
                    $response = array('body' => $response);
                }
            } elseif(is_callable($parsed['callback'])) {
                $this->container->request->params = $parsed['params'];
                
                $response = call_user_func_array($parsed['callback'], array_values($parsed['params']));
            } else {
                $response = false;
            }
            
            $this->container->response->render($response);
        } else {
            $this->defaultNotFound();
        }
    }

    /**
     * Generate diagnostic template markup
     *
     * This method accepts a title and body content to generate an HTML document layout.
     *
     * @param  string   $title  The title of the HTML template
     * @param  string   $body   The body content of the HTML template
     * 
     * @return string
     */
    protected static function generateTemplateMarkup($title, $body)
    {
        return sprintf("<html><head><title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>", $title, $title, $body);
    }

    /**
     * Default Not Found handler
     * @return void
     */
    protected function defaultNotFound()
    {
        echo static::generateTemplateMarkup('404 Page Not Found', '<p>The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly. If all else fails, you can visit our home page at the link below.</p><a href="' . $this->container->request->url . '/">Visit the Home Page</a>');
    }

}
