<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		$multidata = ORM::factory('station'); 
		
		// $query = DB::select()->from('stations')->where('station_id' , '=', 13); 
		// $results = $query->execute(); 
		// foreach($results as $res)
		// {
			// echo $res['station_name']; 
		// }

		// $query = DB::query(Database::INSERT, "insert into stations values ('110', 'big ten' , '10', '10');"); 
		// $query->execute(); 
		
		// $query = DB::insert('stations', array('station_id', 'station_name'))->values(array('120', 'somename'));
		// $query->execute(); 
		
		$query = DB::query(Database::DELETE, "delete from stations");
		$query->execute(); 
		



		// $data = $multidata->find(13);
		// echo $data->station_name; 
		
		//echo $data->data; 
		// $multidata->data = 200; 
		// $multidata->station_id = 15; 
		// $multidata->station_name = 'hi'; 
		// $multidata->coordinatex = 10.10; 
		// $multidata->coordinatey = 20.20; 
		// echo "Hello World!"; 
		// $multidata->save(); 

		// $multidata->find('station_id', 4); 
		// $multidata->delete(); 
		
		//$this->response->body('hello, world!');
	}
	
	public function action_foobar()
	{
		$this->response->body('and you see foobar ;)');
	}

} // End Welcome
