<?php 

namespace MVC\Controllers;

/**
* Description of UserController
*/
class UserController extends \MVC\Controller
{

	public function index( $mvc )
	{
		$m = new \MVC\Models\User;
		$values = $m->all();
		return array("key" => $values);
	}

}
	