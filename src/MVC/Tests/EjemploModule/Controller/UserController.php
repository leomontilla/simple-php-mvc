<?php

namespace MVC\Tests\EjemploModule\Controller;

use MVC\Controller,
    MVC\Tests\Models\User,
    MVC\MVC;

/**
 * User Controller Example
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class UserController extends Controller
{

    /**
     * Example of index action for someone route with the use of the a User model
     * Using the render view
     * @access public
     * @param MVC $app
     * @return string
     */
    public function index(MVC $app)
    {
        $userModel = new User($app->getKey('pdo'));
        $users = $userModel->findAll();
        
        return $app->view()->render('userController/index.html', array(
            'users' => $users
        ));
    }

}
