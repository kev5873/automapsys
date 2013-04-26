<?php defined('SYSPATH') or die('No direct script access.');

// include 'xmlparser.php'; 

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
	return addslashes(getcwd().'a/s/'.$recentfile); 
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

	public function getStyle($line) 
	# style: 4 = Multi
	# style: 0 = f, f
	# style: 1 = f, t
	# style: 2 = t, f
	# style: 3 = t, t
	{
		// $mid = explode($line, ':');

		// print_r($mid); 

		// if(count($mid) != 2)
		// {
		// 	return false; 
		// }

		// $sub = trim($mid[1]); 

		if( strcasecmp( $sub , 'Multi' ) == 0 )
		{
			return 4; 
		} 

		$mid2 = explode($sub, ','); 
		if( count($mid2) != 2 )
		{
			return false; 
		}
		$style = 0; 


		if( strcasecmp(trim($mid2[0]), 't') == 0 )
		{
			$style += 2;
		}

		if( strcasecmp( trim($mid[1]) ,'t') == 0 )
		{
			$style += 1;
		}
		return $style; 

	}

	public function supersubstitute($url)
	{
		$linenum = 0; 
		$order = 1; 
		$file = fopen ($url, "rb");
		$style = 0; // means not 'f, f';  
		  if ($file) {
			while(!feof($file))
			{
				$line = fgets($file); 
				$linenum += 1; 

				if( $linenum == 1) {echo $line."<br />"; continue; }
				if( $linenum == 2) {echo $line."<br />"; $style = $this->getStyle($line); continue; }

				// if(stripos($line, "type"))
				// {
				// 	if(stripos($line, "f")){
				// 		$style = true; 
				// 	}
				// 	continue; 
				// }

				if($style == 0)
				{
					echo "INSERT INTO station_order VALUES ('.', 'line_id', $line, $order, FALSE, FALSE);"."<br />"; 
				}
				if($style == 1)
				{
					echo "INSERT INTO station_order VALUES ('.', 'line_id', $line, $order, FALSE, TRUE);"."<br />"; 
				}
				if($style == 2)
				{
					echo "INSERT INTO station_order VALUES ('.', 'line_id', $line, $order, TRUE, FALSE);"."<br />"; 
				}
				if($style == 3)
				{
					echo "INSERT INTO station_order VALUES ('.', 'line_id', $line, $order, TRUE, TRUE);"."<br />"; 
				}
				if($style == 4)
				{
					$linestuff = explode(',' , $line); 

					$parttime = 'f'; 
					$nighttime = 'f';
					if(count($linestuff) > 3 && $linestuff[1] == 't')
						$parttime = 't';
					if(count($linestuff) > 3 && $linestuff[2] == 't')
						$timetime = 't';
					echo "INSERT INTO station_order VALUES ('.', 'line_id', $linestuff[0], $order, $parttime, $nighttime);"."<br />"; 
				}
				$order += 1;
			}
		}		  
	}

	  // $newf = fopen ($newfname, "wb");

		 //    if ($newf)
		 //    while(!feof($file)) {
		 //      fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
		 //    }
		 //  }

		 //  if ($file) {
		 //    fclose($file);
		 //  }

		 //  if ($newf) {
		 //    fclose($newf);
		 //  }

	public function action_index()
	{
		echo 'Welcome!';

		$feeder = new Model_feed(); 

// $line_id, $start_station, $end_station, $start_time, $end_time, $service_replace_id, $filename)

		// $stationset = array( 10, 30, 20, 40, 50, 60 ); 
		// $feeder->insertToLineInfoArray( 6, $stationset, NULL,  NULL, NULL, 0, 'asdf'.time() );
		$feeder->insertToLineInfo( 18, NULL, NULL, NULL,  NULL, NULL, 2, 'asdf' );

		// echo "<br />"; 

// $sets = array( array( "line_name" => "  " )  )
// foreach($sets as $set)
// {
// 		print_r($feeder->getStationWithOrder( $set["line_name"] , $set["station_name"] ) ); 
// 		echo "<br />";
// }


		// print_r( $feeder->getStationWithOrder( "[6]" , "116" ) ); 


		// $pgconn = pg_connect('host=localhost dbname=ams user=postgres password=root'); 
		// pg_insert($pgconn, 'multidata' , array('data'=>100)); 
		// $multidata = ORM::factory('station'); 
			// echo "Welcome! <br />";
			// echo $this->getUpToDateStatus(); 
		// print_r($this->foobar()); 
		// echo "<br />";
			// $this->no_train_case(); 
			// $start=$_GET["start"];
			// $end=$_GET["end"];
			// for($i=$start; $i < $end+1; $i++)
			// 	echo $i."<br />";
			// $this->supersubstitute("C:/users/Kenneth Li/dropbox/7_line.txt");  
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
