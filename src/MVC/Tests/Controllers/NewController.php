<?php

/**
 * Description of NewController
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\Controllers
 */

namespace MVC\Tests\Controllers;

use MVC\Controller,
    MVC\MVC;

class NewController extends Controller 
{

    /**
     * Example of index action for someone route
     * @access public
     * @param MVC $app
     * @return array
     */
    public function index(MVC $app)
    {
        return array('users' => array(1, 2, 3));
    }

}
