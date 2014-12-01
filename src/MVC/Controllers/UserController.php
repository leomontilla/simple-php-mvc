<?php

/**
 * User Controller Example
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */

namespace MVC\Controllers;

use MVC\Controller,
    MVC\Models\User,
    MVC\MVC;

class UserController extends Controller
{

    /**
     * @param MVC $app
     * 
     * @return array
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
