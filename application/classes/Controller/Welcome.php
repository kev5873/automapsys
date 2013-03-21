<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$multidata = ORM::factory('multidata'); 
		$this->response->body('hello, world!');
	}
	
	public function action_foobar()
	{
		$this->response->body('and you see foobar ;)');
	}

} // End Welcome
