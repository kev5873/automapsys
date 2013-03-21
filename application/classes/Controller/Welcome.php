<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		// $pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		$multidata = ORM::factory('station'); 
		 $data = $multidata->find('station_id', 1);
		// echo $data->data; 
		// $multidata->data = 200; 
		$multidata->station_id = 1; 
		$multidata->station_name = 'hi'; 
		$multidata->coordinatex = 10.10; 
		$multidata->coordinatey = 20.20; 
		
		// $multidata->save(); 
		$this->response->body('hello, world!');
	}
	
	public function action_foobar()
	{
		$this->response->body('and you see foobar ;)');
	}

} // End Welcome
