<?php defined('SYSPATH') or die('No direct script access.');

include 'xmlparser.php'; 

class Controller_Welcome extends Controller {


private function downloadFile ($url, $path) {

  $newfname = $path;
  $file = fopen ($url, "rb");
  if ($file) {
    $newf = fopen ($newfname, "wb");

    if ($newf)
    while(!feof($file)) {
      fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
    }
  }

  if ($file) {
    fclose($file);
  }

  if ($newf) {
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

	public function betweens($text)
	{
		$pattern = '(between|use|)'; 
	}

	public function isStationByName($name)	// attempts to determine the name is a station; 
	{
		return true;// left this way until someone fixes database; 
	}

	public function getTrains($sentence) // function prefers sentences over strings, but strings works fine as well. 
	{
			$pattern = '(\[.\])';	// train phrase is not necessary, [.] implies train;  
			$trains = array(); 
			preg_match($pattern, $text, $trains, $pos+1);	
			$traindata = array(); 
			foreach($trains as $train)
			{
				$higheststartpos = 0; 
				for($j = count($traindata)-1; $j >= 0; $j--)
				{
					$trainx = $traindata[$j][0]; 
					if($train == $trainx)
					{
						$higheststartpos = $traindata[$j][1]; 
						break; 
					}
				}
				$traindata[] = array($train, stripos($sentence, $train, $higheststartpos)); 
			}
			return $traindata;  
	}

	public function getStations($sentence)	// function prefers sentences over strings, but strings works fine as well. 
	{
		$parser = new XMLParser($filestuff); 
		$parser->Parse(); 

		$strongs = $parser->document->strong;
		$stationArr = array(); 

		foreach($strongs as $station)
		{
			if($this->isStationByName($station))
			{
				$stationArr[] = $station; 
			}
		} 
		return $stationArr; 
	}

	public function no_train_case()
	{
		$textArr = $this->foobar(); 
		foreach($textArr as $text){
			$text = htmlentities($text); 
			//$parser = new XMLParser($text);
			$pos = stripos($text, 'No');
			if(!pos){return false;}	// check if its a no-type, check #1; 

			$pattern = '(\[.\])';	// train phrase is not necessary, [.] implies train;  
			$train = array(); 
			preg_match($pattern, $text, $train, $pos+1);

			print_r($matches);  
		}
	}

	public function action_index()
	{
		// $pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		// $multidata = ORM::factory('station'); 
			echo "Welcome! <br />";
			// echo $this->getUpToDateStatus(); 
		print_r($this->foobar()); 
		echo "<br />";
			$this->no_train_case(); 

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
