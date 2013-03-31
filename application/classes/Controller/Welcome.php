<?php defined('SYSPATH') or die('No direct script access.');

include 'xmlparser.php'; 

class Controller_Welcome extends Controller {


	private function downloadFile ($url, $path) {
		$newfname = $path;
		$file = fopen ($url, "rb");
		if ($file){
			$newf = fopen ($newfname, "wb");

			if ($newf)
			while(!feof($file))
			{
		    	fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
		    }
		}

		if($file)
		{
			fclose($file);
		}

		if($newf)
		{
			fclose($newf);
		}
	}
 
	public function getUpToDateStatus()	// returns the filename of the latest status file; 
	{
	$statuses = scandir(addslashes(getcwd().'\a\s')); 
	$recentfile = $statuses[0]; 
	foreach( $statuses as $status  )
	{
		if($status > $recentfile){
			$recentfile = $status;} 
	}
	// return $recentfile; 
	return addslashes(getcwd().'/a/s/'.$recentfile); 
	// return file_get_contents(addslashes(getcwd().'/a/s/'.$recentfile)); 
	}	
	
	public function foobar()	// returns the text on the mta feeds for the latest downloaded mta feed; 
	{
		$newfile = $this->getUpToDateStatus(); 
		$filestuff = file_get_contents($newfile); 
		$parser = new XMLParser($filestuff); 
		$parser->Parse(); 
		
		$mass = $parser->document->subway[0]; 
		$skipfirst = true; 
		$textArr = array(); 
		foreach($mass->line as $linemen){
			$text = $linemen->text[0]->tagData;
			if(trim($text) != '') 
				$textArr[] = $text; 
		} 
		return $textArr; 
	}

	// Whats this doing here it should be a model!!!
	class ServiceChance()
	{
		$train; // The train that is in question; 
		$direction; // The direction of hte train; 
		$affectedAreas = array(); // affected stops; Could be a string of arrays; 
		$startArea;
		$endArea; 
		// The affected stops between startArea and endArea; 
		$type; // type of change (skip, reroute); 
		$timeStart; // time start; This will use unix time;  
		$timeEnd; // time end; This will use unix time; 
	}

	public function ChangeParser($changeText)	
	{
		$wdn = array(); // aka the list of strings that are vital to the change; 


	}
	public function action_index()
	{
		// $pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		// $multidata = ORM::factory('station'); 
			echo "Welcome! <br />";
			// echo $this->getUpToDateStatus(); 
			print_r($this->foobar()); 
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
		
		// $query = DB::query(Database::DELETE, "delete from stations");
		// $query->execute();		
		
		// $feed = new Model_feed();
		
		// $feed->downloadFeed(); 

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
