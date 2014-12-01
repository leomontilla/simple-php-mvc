# Simple PHP-MVC

Plantilla con el Modelo Vista Controlador

### Tabla de Contenidos
- [Instalación](#instalacion)
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
- [Proveedores o servicios](#providers)
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

### <a name='instalacion'></a> Instalación

Usa composer para instalar.
> - Crea una carpeta con el nombre de tu proyecto.
> - Entra en la carpeta que creaste y agrega un archivo composer.json con el contenido

```
{
   "require": {
      "rameduard/simple-php-mvc": '1.2.1'
   }
}
```
> - Ahora abre una terminal de tu sistema operativo y ejecuta en la carpeta del proyecto **composer install** y espera a que se instale Simple PHP MVC.

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
<?php

namespace ControllersNamespace;

use MVC\Controller,
    MVC\MVC,
    MVC\Server\Request;

class Nombre_del_Controlador extends Controller
{
   public function nombre_de_la_accion(MVC $mvc, Request $request)
   {
       return '<p>Resultado de la accion</p>';
   }
}
```

## <a name='modelos'></a> Modelos
Un modelo se crea de la siguiente forma:
``` 
<?php
namespace ModelsNamespace;

use MVC\DataBase\Model,
    MVC\DataBase\PDO;

class Nombre_del_Modelo extends Model
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'nombre_tabla');
    }
}
```

## <a name='rutas'></a> Rutas
Una ruta se representa como cualquier URI con métodos de consulta que se envía al servidor. 

#### <a name='rutas-get'></a> GET
Usa el método **get()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP GET**.
```
$mvc = new \MVC\MVC();
$mvc->get("/hello/[a:name]", function($name) {
    return "Hello $name.";
}, 'hello_get');
```
#### <a name='rutas-post'></a> POST
Usa el método **post()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP POST**.
```
$mvc = new \MVC\MVC();
$mvc->post("/hello/[a:name]", function($name) {
    return "Hello $name";
}, 'hello_post');
```
#### <a name='rutas-delete'></a> DELETE
Usa el método **delete()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP DELETE**.
```
$mvc = new \MVC\MVC();
$mvc->delete("/hello/[i:id]", function($id) {
    return "DELETE $id";
}, 'hello_delete');
```
#### <a name='rutas-put'></a> PUT
Usa el método **put()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP PUT**.
```
$mvc = new \MVC\MVC();
$mvc->put("/hello/[i:id]", function($id) {
    return "PUT $id";
}, 'hello_put');
```
#### <a name='rutas-options'></a> OPTIONS
Usa el método **options()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP OPTIONS**.
```
$mvc = new \MVC\MVC();
$mvc->options("/hello/[i:id]", function($id) {
    return "OPTIONS $id";
}, 'hello_options');
```
#### <a name='rutas-head'></a> HEAD
Usa el método **head()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP HEAD**.
```
$mvc = new \MVC\MVC();
$mvc->head("/hello/[i:id]", function($id) {
    return "HEAD $id";
}, 'hello_head');
```
#### <a name='rutas-ajax'></a> AJAX
Usa el método **ajax()** de tu aplicación u objeto **MVC** para crear recursos que devuelvan una llamada a un **URI** mediante el método **HTTP AJAX**.
```
$mvc = new \MVC\MVC();
$mvc->ajax("/hello/[i:id]", function($id) {
    return "AJAX $id";
}, 'hello_ajax');
```
## <a name='rutas-group'></a> Grupos de rutas
Usa el método group de tu aplicación u objeto **MVC** para crear recursos de rutas agrupadas. Esto es para agrupar grupos de rutas que tienen el mismo prefijo.
```
$mvc = new \MVC\MVC();
$mvc->group("/admin", function($route) use($mvc) {
    $mvc->($route, function() {
        return "Admin index";
    }, 'admin_index');
    $mvc->("$route/other", function() {
        return "Admin other route.";
    }, 'admin_other');
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
    return "AJAX id = $id, name = $name\n";
}, 'hello_id_name');
```
## <a name='redirect'></a> Redireccionamiento
Esta función redirecciona a una ruta...
```
$mvc = new \MVC\MVC();
$mvc->get("/", function() use($mvc){
    $mvc->redirect('/redirect');
}, 'home_redirect');
$mvc->get("/redirect", function(){
    return "Redirect\n";
}, 'redirect');
```
## <a name='providers'></a> Proveedores o servicios
Este aspecto es para registrar otros servicios independientes del Simple PHP MVC implementando la interfaz MVC\ProviderInterface. Por ejemplo: Doctrine Object Relational Mapper, SwiftMailer, Monolog, etc. 
```
namespace MVC\Providers;

use MVC\MVC,
    MVC\ProviderInterface;

class ExampleProvider implements ProviderInterface
{
    
    public function boot(MVC $app) {
        print "Boot" . $app->getKey('example_name');
    }

    public function register(MVC $app) {
        
        $app->setKey('example.name', get_class($this));
        
        print "Register Example Provider";
        
    }

}
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
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MVC\MVC;

$mvc = new MVC();
    
$mvc->get("/", function() {
    return "Hola mundo";
}, 'hola_mundo');

$mvc->run();
```

### <a name='ejemplo2'></a> Ejemplo2: Usando Modelos, Vistas y Controladores
Configuracion del archivo: `/` **index.php**
``` 
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MVC\DataBase\PDO,
    MVC\MVC;

$mvc = new MVC();

$app->setKey('pdo', new PDO('mysql:host=localhost;dbname=database;charset=UTF8', 'root', ''));

$mvc->get("/", function() use($mvc) {
    print_r($mvc);
    return "Respuesta";
}, 'index');

$mvc->get("/users", 'MVC\\Controllers\\UserController::index', 'users');

$mvc->get("/users_call", function() use($mvc) {
    print "Using the Model View Controller App\n";

    $uc = new \MVC\controllers\UserController;
    $uc->view()->templates_path = "./src/MVC/Views";

    return $uc->call($mvc, $mvc->request(), 'index', 'index.html');;
}, 'users_call');

$mvc->notFound(function() use($mvc) {
    $mvc->render("404.html", array("uri" => $mvc->request()->url), 404);
});

$mvc->run();
```
Vista de la ruta /users y /users_call: `MVC/Views/userController/` **index.html**
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
        <div><?php print_r($users)?></div>
    </body>
</html>
```
Controlador que crea la vista: `MVC/Controllers/` **UserController.php**
``` 
<?php

namespace MVC\Controllers;

use MVC\Controller,
    MVC\Models\User,
    MVC\MVC;

class UserController extends Controller
{
   public function index(MVC $mvc)
   {
       $um = new User($mvc->getKey('pdo'));
       $users = $um->findAll();
       return $app->view()->render('userController/index.html', array(
            'users' => $users
        ));
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
