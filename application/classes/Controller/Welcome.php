<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		// $pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		$multidata = ORM::factory('multidata'); 
		$data = $multidata->find('multi', '100'); 
		// $multidata->data = 100; 
		// $multidata->save(); 
		$this->response->body('hello, world!');
	}
	
	public function action_foobar()
	{
		$this->response->body('and you see foobar ;)');
	}

} // End Welcome
