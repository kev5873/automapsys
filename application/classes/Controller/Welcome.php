<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		// $pgconnection = pg_connect('host=localhost port=5432 dbname=ams user=postgres password=root'); 
		$multidata = ORM::factory('multidata'); 
		$this->response->body('hello, world!');
	}

} // End Welcome
