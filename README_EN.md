# Simple PHP -MVC

Template with the Model View Controller

### Table of Contents
- [Settings](#settings)
- [Routes, Views and Controllers](#rut-vis-cont)
- [Models](#models)
- [Unit Testing](#tests)
- [Example](#example)
- [Author](#author)

### <a name='settings'> </a> Settings
> - **In Linux:** Once installed make sure that the root folder has the appropriate system with `sudo chmod 777- R` permissions.
> - Configuration files are **database.php** and **bootstrap.php**, found in `app/config` folder.
> - Values of file **database.php** only must be modified for the connection to the database.

### <a name='rut-vis-cont'> </a> Routes , Views and Controllers
> 1. Routes
>   - In the file `config/AppRoutes.php` routes you can configure all your application routes .
>   - This class simply returns an array of paths in the `config/bootstrap.php` file to invoke the `\app\src\lib\server\Dispatcher` that allow actions to run routes.
> 2. Views
>   - The views must be files with `.html`.
>   - The names of these files must be equal to the action of the controller. Example: **action/controller** `index`, **file/view** `index.html`
>   - The files should be saved in the ` views ` folder with the name of the class starting with tiny controller. Example: **folder** `classController` where classController -> ClassController.
> 3. Controllers
>   - Each controller extends `\app\controllers\AppController` .
>   - Each action involved with a view must have a parameter `$request` and must return.
>   - The return of each controller must be `strings` or variables with values ​​of any type .
>   - To send multiple variables to a view, be of any type , the driver must return the variables within the `compact()` . Example: **$variable1 , $variable2 , $variable3** `return compact ( 'variable1', 'variable2', 'variable3')`

### <a name='models'> </a> Models
A model is created as follows :
```
    namespace app \ models ;
    model_name class extends \app\database\Functions_DB { 
        public function __ construct () {
           $this->table = "table_name";
        }
    }
```
> ** NOTE : ** All model should be included in the `app/models` folder .


### <a name='tests'> </a> Unit Tests
There is a module for unit testing of classes. With a nice view of [ VisualPHPUnit ] ( https://github.com/NSinopoli/VisualPHPUnit ) . Just go to the url `{ system } / tests ` .
> **NOTE:** To add remember to add unit tests in the `tests/tests` folder.
> **NOTE 2:**
> - Please, enter a file name equal to the name of the class of unit test specific example : ` NameClassTest.php ` .
> - Each unit test class must have the suffix `Test` in your name.

### <a name='example'> </a> Example:
Array of the route in `app/config/` **AppRoutes.php**
```
    array ( array ( 'get' , 'post' ) , '/ ', function ( $ request ) {
        $ic = new \app\controllers\IndexController;
        return $ic->call( "index" , $request );
    } )
```
View of the route : `app/views/IndexController/` **index.html**
```
    My first Vista using Vista and Controllador
    <? php print_r ($array1 ) >
    <? php print_r ($array2 ) >
```
Controller creates the view : `app/controllers/` **IndexController.php**
```
    namespace app \ controllers ;
    IndexController class extends \app\controllers\AppController
    {
       public function index ( $request )
       {
           $array1 = array ("value", "Another Value", "more value", 1, array ("other arrangement"));
           $array2 = array ("value1" => "value" , "value2" => "otrovalor");
           return compact ( 'array1', 'array2');
       }
    }
```

### <a name='author'> </a> **Author:** Ramón Serrano <ramon.calle.88@gmail.com>
