<?php

namespace MVC\Tests\EjemploModule\Controller;

use MVC\Controller;
use MVC\MVC;
use MVC\Server\HttpRequest;

/**
 * Description of FooController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class FooController extends Controller
{

    public function fooAction(MVC $mvc, HttpRequest $request)
    {
        return 'Foo Response';
    }

}
