# Simple PHP-MVC

Plantilla con el Modelo Vista Controlador

### Tabla de Contenidos
- [Configuración](#configuracion)
- [Rutas, Vistas y Controladores](#rut-vis-cont)
- [Modelos](#modelos)
- [Rutas](#rutas)
    - [GET](#rutas-get)
    - [POST](#rutas-post)
    - [DELETE](#rutas-DELETE)
    - [PUT](#rutas-put)
    - [OPTIONS](#rutas-options)
    - [HEAD](#rutas-head)
    - [AJAX](#rutas-ajax)
    - [Grupos de rutas](#rutas-group)
- [Parámetros de rutas](#rutas-params)
- [Redireccionamiento](#redirect)
- [Otros aspectos](#otros)
    - [controller($name = null)](#otros-controllers)
    - [model($name = null)](#otros-models)
    - [view()](#otros-view)
    - [request()](#otros-request)
    - [response()](#otros-response)
- [Pruebas Unitarias](#pruebas)
- [Ejemplo: Hola mundo](#hola-mundo)
- [Ejemplo2: Usando Modelos y Controladores](#ejemplo2)
- [Usando la consola](#usando-consola)
- [Autor](#autor)

### <a name='configuracion'></a> Configuración
> - **En Linux:** Una vez instalado asegúrate de que la carpeta raíz del sistema tenga los permisos apropiados con `sudo chmod 777 -R`.
> - Para los parámetros de la configuración de la base de datos **config-database.php**.
> - Si hay algún parámetro erróneo el sistema arrojará un Exception error.

### <a name='rut-vis-cont'></a> Rutas, Vistas y Controladores
> 1. Rutas
>   - En el archivo index.php puedes configurar las acciones para las rutas que deseas configurar. Todo esto en el archivo index.php por recomendación.
> 2. Vistas
>   - Las vistas debe ser archivos con extensión `.html` o `.php`. 
>   - Si usa un controlador para mostrar la vista, los nombres de estos archivos deben ser iguales a la accion del controlador. Ejemplo: **accion/controlador** `index`, **archivo/vista** `index.html`
>   - Los archivos deben ser guardados en la carpeta `views` con el nombre de la clase del controlador iniciando con minúscula. Ejemplo: **carpeta** `claseControlador` donde claseControlador -> ClaseControlador.
> 3. Controladores
>   - Cada controlador extiende de `\MVC\Controller`.
>   - Cada accion involucrada con una vista debe tener un parámetro `$app` y debe tener retorno.
>   - El retorno de cada controlador deben ser `cadenas de texto` o variables de tipo `arreglos asociativos` con valores de cualquier tipo.
>   - Para enviar varias variables a una vista, sean de cualquier tipo, el controlador debe retornar las variables dentro de la función `array()`. Ejemplo: **$variable1, $variable2, $variable3** `return array( 'variable1' => $variable1, 'variable2' => $variable2, 'variable3' => $variable3 )`

## <a name='controladores'></a> Controladores
``` 
    namespace MVC\controllers;
    
    class Nombre_del_Controlador extends \MVC\Controller
    {
       public function nombre_de_la_accion( $mvc )
       {
           //Variables, condiciones y acciones
           //retornando valores para que sean mostrados por las vistas
           $cadena = "Valores";
           $arreglo = array("Valores");
           $objeto = new \stdClass;
           return array("cadena" => $cadena, "arreglo" => $arreglo, "objeto" => $objeto);
       }
    }
```
> **NOTA:** Todo controlador debe incluirse en la carpeta `MVC/controllers`.

## <a name='modelos'></a> Modelos
Un modelo se crea de la siguiente forma:
``` 
    namespace MVC\models;
    
    require dirname(dirname(__DIR__)) . "/MVC/database/DB.php";
    require dirname(dirname(__DIR__)) . "/MVC/errors/Exception.php";
    require dirname(dirname(__DIR__)) . "/MVC/database/Functions_DB.php";
    
    class Nombre_del_Modelo extends \MVC\database\Functions_DB{
        public function __construct(){
            $path_config_file = dirname(__DIR__) . "/config-database.php";
            parent::__construc($path_config_file);
            $this->table = "nombre_de_tabla";
        }
	}
```
> **NOTA:** Todo modelo debe incluirse en la carpeta `MVC/models`.

## <a name='rutas'></a> Rutas
Una ruta se representa como cualquier URI con métodos de consulta que se envía al servidor. 

#### <a name='rutas-get'></a> GET
Usa el método **get()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP GET**.
```
$mvc = new \MVC\MVC();
$mvc->get("/hello/[a:name]", function($name) use($mvc) {
    print "Hello $name\n";
    print_r($mvc->request()->params);
});
```
#### <a name='rutas-post'></a> POST
Usa el método **post()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP POST**.
```
$mvc = new \MVC\MVC();
$mvc->post("/hello/[a:name]", function($name) use($mvc) {
    print "Hello $name\n";
    print_r($mvc->request()->params);
});
```
#### <a name='rutas-delete'></a> DELETE
Usa el método **delete()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP DELETE**.
```
$mvc = new \MVC\MVC();
$mvc->delete("/hello/[i:id]", function($id) {
    print "DELETE $id\n";
});
```
#### <a name='rutas-put'></a> PUT
Usa el método **put()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP PUT**.
```
$mvc = new \MVC\MVC();
$mvc->put("/hello/[i:id]", function($id) {
    print "PUT $id\n";
});
```
#### <a name='rutas-options'></a> OPTIONS
Usa el método **options()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP OPTIONS**.
```
$mvc = new \MVC\MVC();
$mvc->options("/hello/[i:id]", function($id) {
    print "OPTIONS $id\n";
});
```
#### <a name='rutas-head'></a> HEAD
Usa el método **head()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP HEAD**.
```
$mvc = new \MVC\MVC();
$mvc->head("/hello/[i:id]", function($id) {
    print "HEAD $id\n";
});
```
#### <a name='rutas-ajax'></a> AJAX
Usa el método **ajax()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP AJAX**.
```
$mvc = new \MVC\MVC();
$mvc->ajax("/hello/[i:id]", function($id) {
    print "AJAX $id\n";
});
```
## <a name='rutas-group'></a> Grupos de rutas
Usa el método group de tu aplicación u objeto **MVC** para crear recursos de rutas agrupadas. Esto es para agrupar grupos de rutas que tienen el mismo prefijo.
```
$mvc = new \MVC\MVC();
$mvc->group("/admin", function($route) use($mvc) {
    $mvc->($route, function(){
        print "Print admin index";
    });
    $mvc->("$route/other", function(){
        print "Print admin other route.";
    });
});
```
## <a name='rutas-params'></a> Parámetros de rutas
Los tipos de variables válidos para las rutas son:

 - **[i]** Entero
 - **[a]** Alfanumerico
 - **[h]** Hexadecimal
 - **[*]** Cualquier valor

```
$mvc = new \MVC\MVC();
$mvc->ajax("/hello/[i:id]/[a:name]", function($id, $name) {
    print "AJAX id = $id, name = $name\n";
});
```
## <a name='redirect'></a> Redireccionamiento
Esta función redirecciona a una ruta...
```
$mvc = new \MVC\MVC();
$mvc->get("/", function() use($mvc){
    $mvc->redirect('/redirect');
});
$mvc->get("/redirect", function(){
    print "Redirect\n";
});
```
## <a name='otros'></a> Otros aspectos
Para usar los objetos Response, Request, Controller, View y Model, están las siguientes funciones:

### <a name='otros-controller'></a> controller($name = null)
Dependiendo del nombre del controlador, si está cargado en el núcleo se devuelve el controlador. Sino existe devuelve el controlador por defecto.
```
$mvc = new \MVC\MVC();
$controller = $mvc->controller();
```
### <a name='otros-models'></a> model($name)
Dependiendo del nombre del modelo, si está cargado en el núcleo se devuelve el modelo.
```
$mvc = new \MVC\MVC();
$model = $mvc->model('Nombre_del_Modelo');
```
### <a name='otros-view'></a> view()
Devuelve el objeto de la vistas.
```
$mvc = new \MVC\MVC();
$view = $mvc->view();
```
### <a name='otros-request'></a> request()
Devuelve el objeto \MVC\server\Request.
```
$mvc = new \MVC\MVC();
$request = $mvc->request();
```
### <a name='otros-response'></a> response()
Devuelve el objeto \MVC\server\Response.
```
$mvc = new \MVC\MVC();
$response = $mvc->response();
```
### <a name='hola-mundo'></a> Ejemplo: Hola mundo

```
    require "lib/MVC/MVC.php";

    \MVC\MVC::registerAutoloader();
    
    $mvc = new \MVC\MVC();
    
    $mvc->get("/", function() {
        print "Hola mundo";
    });
    
    $mvc->run();
```

### <a name='ejemplo2'></a> Ejemplo2: Usando Modelos, Vistas y Controladores
Configuracion del archivo: `/` **index.php**
``` 
    require "lib/MVC/MVC.php";

    \MVC\MVC::registerAutoloader();
    $config = array(
        "app_path" => __DIR__,
        "controllers" => array(
            "UserController" => "\\MVC\\controllers\\UserController"
            )
        );
    $mvc = new \MVC\MVC($config);
    
    $mvc->get("/", function() use($mvc) {
        print "Respuesta /\n";
        print_r($mvc);
    });
    
    $mvc->get("/mvc", function() {
        print "Using the Model View Controller App\n";

        $uc = new \MVC\controllers\UserController;
        $uc->view()->root = __DIR__;
        $uc->view()->templates_path = "./lib/MVC/views";
        echo $uc->call('index', $mvc->request(), "index.html")->body;
    });
    
    $mvc->get("/mvc2", function() use($mvc) {
        print "Using the Model View Controller App\n";

        $uc = $mvc->controller('UserController');
        $uc->view()->root = __DIR__;
        $uc->view()->templates_path = "./lib/MVC/views";
        echo $uc->call('index', $mvc->request(), "index.html")->body;
    });
    
    $mvc->notFound(function() use($mvc) {
    	$mvc->render("404.html", array("uri" => $mvc->request()->url), 404);
    });
    
    $mvc->run();
```
Vista de la ruta /mvc y /mvc2: `MVC/views/userController/` **index.html**
``` 
    <!DOCTYPE html>
    <html>
        <head>
            <title>Ejemplo 1</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width">
        </head>
        <body>
            <p>Response</p>
            <div><?php print_r($key)?></div>
        </body>
    </html>

```
Controlador que crea la vista: `MVC/controllers/` **UserController.php**
``` 
    namespace MVC\controllers;
    class UserController extends \MVC\Controller
    {
       public function index( $mvc )
       {
           $m = new \MVC\models\User;
           $values = $m->all();
           return array("key" => $values);
       }
    }
```
### <a name='usando-consola'></a> Usando la consola

1. Entrar en la carpeta de tu proyecto:
  - Dependiendo del servidor que tengas debes entrar en la carpeta del proyecto.
  - En windows si tienes wamp ```cd C:\wamp\www\``` más el nombre de la carpeta raíz del proyecto.
  - En Linux si tienes xampp ```cd /opt/lampp/htdocs/``` más el nombre de la carpeta raíz del proyecto.
2. Ya que estes en la carpeta del proyecto ejecutar ```php command --help```, y se te mostrara los comandos que se pueden ejecutar en la actual versión.
3. El comando ```php command build_module``` te creará un ejemplo de Modelo, Vista y Controlador.
4. El comando ```php command build_controller``` te creará un Controlador con el nombre que le introduzcas.
4. El comando ```php command build_model``` te creará un Modelo con el nombre que le introduzcas.

### <a name='autor'></a> **Autor:** Ramón Serrano <ramon.calle.88@gmail.com>
