<?php 

namespace MVC\controllers;

/**
* Description of UserController
*/
class UserController extends \MVC\Controller
{

	public function index( $mvc )
	{
		$m = new \MVC\models\User;
		$values = $m->all();
		return array("key" => $values);
	}

}
	