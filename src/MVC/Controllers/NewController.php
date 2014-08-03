<?php 

namespace MVC\Controllers;

/**
* Description of NewController
*/
class NewController extends \MVC\Controller
{

	public function index( $app )
	{
		$m = new \MVC\Models\User;
		$values = $m->all();
		return array("key" => $values);
	}

}
	