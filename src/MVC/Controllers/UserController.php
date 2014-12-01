<?php

/**
 * User Controller Example
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\Controllers;

use MVC\Controller,
    MVC\Models\User,
    MVC\MVC;

class UserController extends Controller
{

    public function index(MVC $app)
    {
        $userModel = new User($app->getKey('pdo'));
        $users = $userModel->findAll();
        return array("users" => $users);
    }

}
