<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public $filestart = "a/s/status-1367364046.xml"; 

	public function downloadFeed()
	{
		$result =  shell_exec( getcwd(). "/a/s/ams12" ); 
		if( isset( $result ) ) 
		{
			return; 
		}

		$url         = 'http://www.mta.info/status/serviceStatus.txt';
		$currentTime = time();
		$path        = getcwd()."/a/s/status-".$currentTime.".xml";
		$newfname    = $path;
		$file        = fopen ($url, "rb");
		if($file)
		{
			$newf = fopen ($newfname, "wb");
			if($newf)
			{
				while(!feof($file))
				{
					fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
				}
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
		return "a/s/status-".$currentTime.".xml";
	}

	public function processFeed($file) {
		$subway = simplexml_load_file($file);

		$output = array();
		for($i = 0; $i < 11; $i++)
		{
			$singleTrunkLine   = new DOMDocument();
			@$singleTrunkLine->loadHTML($subway->subway[0]->line[$i]->text[0]);
			$trunkLineAdvisory = $singleTrunkLine->getElementsByTagName('a');
			$trunkLineDetail   = $singleTrunkLine->getElementsByTagName('div');

			$changes      = array();
			$changeDetail = array();
			$c1           = 0;
			$c2           = 0;

			foreach($trunkLineAdvisory as $line)
			{
				if(strpos($line->nodeValue, '[') == 1)
				{
					array_push($changes, $line->nodeValue);
					$c1++;
				}
			}
			foreach($trunkLineDetail as $line)
			{
				array_push($changeDetail, $line->nodeValue);
				$c2++;
			}
			if(($c1 != 0 && $c2 != 0) && ($c1 == $c2))
			{
				$output[$i]['change']	    = $changes;
				$output[$i]['changeDetail'] = $changeDetail;
			}

		}

		$retArr = array();

		for($i=0;$i<11;$i++)
		{
			if(isset($output[$i]))
			{
				for($j=0;$j<sizeof($output[$i]['change']);$j++)
				{
					if(isset($output[$i]['change'][$j]))
					{
						$aChange = $output[$i]['change'][$j] . '<br />';
						$bChange = $output[$i]['changeDetail'][$j];
						array_push($retArr, $this->processIndividual($aChange, $bChange, $file));
						//echo '<br />';
						//$retArr[$i]=$this->processIndividual($aChange, $bChange);
					}
				}
			}
		}

		return $retArr;
		
		//$aChange = $output[3]['change'][0] . '<br />';
		//$bChange = $output[3]['changeDetail'][0];
		//$aChange = $output[0]['change'][0] . '<br />';
		//$bChange = $output[0]['changeDetail'][0];
		// $this->processIndividual($aChange, $bChange);
		
		// return $output;

		// Example Usage $output[trunkID][change/changeDetail][route(depends on # of advisories)]
		//echo $output[3]['change'][0] . '<br />';
		//echo $output[3]['changeDetail'][0];
	}

	public function processIndividual($change, $changeDetail, $filename)
	{	
		$change            = strip_tags($change);
		$changeDetail      = strip_tags($changeDetail);
		$trainLine         = $this->findTrain($change);

		//-bound trains skip ..... and .... case
		//[6] Brooklyn Bridge-bound trains skip 116, 110, 103, 96, 77, 68 and 51 Sts
		if(strpos($change, 'skip') > 0)
		{
			$stationString     = substr($change, strpos($change, 'skip ') + 5);

			//uptown downtown determination
			$startIndex        = strpos($change, ' ');
			$endIndex          = strpos($change, '-');
			$boundStation      = substr($change, $startIndex, $endIndex - $startIndex);
			$boundStationOrder = $this->getStationWithOrder('['.$trainLine.']', trim($boundStation));


			//if($boundStationOrder['station_order'] > 1)
				//echo "DOWNTOWN";// Going downtown
			//else
				//echo "UPTOWN";// Going uptown

			//echo $boundStation . '-bound<br />';

			$stationlist = array();

			if(strpos($stationString, 'Sts')>0) //find Sts
			{	
				//echo $stationString.'<br />';
				$stationlist = explode(", ", $stationString);
				//echo $stationlist[sizeof($stationlist)-1].'<br />'; 
				$temp = array();
				$temp = explode(' ', $stationlist[sizeof($stationlist)-1]);
				
				array_pop($stationlist);
				for($i=0;$i<sizeof($temp);$i++)
				{
					$temp[$i] = trim(strip_tags($temp[$i]));
					if(strcmp($temp[$i], 'and')  && strcmp($temp[$i], 'Sts'))
					{
						array_push($stationlist,$temp[$i]);
					}
				}

				//add St into each of the number station
				foreach ($stationlist as &$value) 
				{
					$value = $value.' St';
				}

				$temp = array();
				$stationOrder =array();

				for($i=0;$i<sizeof($stationlist);$i++)
				{
					array_push($stationOrder, $this->getStationWithOrder('['.$trainLine.']', $stationlist[$i])); 
					//echo $stationlist[$i] . ' : ' . $stationOrder[$i]['station_order'] . '<br />';
				}

			}
			else//bound trains skip ..... and .... case
			{
				//echo 'this is else --------------'.$change.'<br />';
				$stationlist = explode(', ', $stationString);
				$temp = explode('and ', end($stationlist));

				array_pop($stationlist);

				foreach($temp as $key)
					array_push($stationlist,$key);
				//array_push($stationlist, $temp);

				foreach ($stationlist as $key) 
				{
					$key= trim(strip_tags($key));
					//echo $key.'---THIS IS STATIONLIST<br />';
				}
					
				

				$stationOrder =array();

				for($i=0;$i<sizeof($stationlist);$i++)
				{
					//echo "TrainLine: $trainLine , StationListName: ".$stationlist[$i]."<br />";
					//$gaga = $this->getStationWithOrder('['.$trainLine.']', $stationlist[$i]); 
					//foreach ($gaga as $key)
					//{
					//	echo $key;
					//}
					//echo $trainLine."-----".$stationlist[$i];
					//echo '<br />';
					array_push($stationOrder, $this->getStationWithOrder('['.$trainLine.']', $stationlist[$i])); 
					//echo $stationlist[$i] . ' : ' . $stationOrder[$i]['station_order'] . '<br />';
				}
			}
		}
		//no train runing case
		else if(strpos($change, 'No trains running') > 0)
		{
			$trainLineId = $this->getStationWithOrder('['.$trainLine.']');
			$this->insertToLineInfo($trainLineId['line_id'], NULL, NULL, NULL, NULL, NULL, 2, $filename);
			return array('trainLine' => $trainLine, 'boundStation' => NULL, 'startStation' => NULL, 'endStation' => NULL, 'changeSummary' => $change, 'changeDetail' => $changeDetail, 'service_replace_id' => 2);
		}
		//no train between case
		else if(strpos($change, 'No trains between') > 0) //
		{
			$stationString     = substr($change, strpos($change, 'between ') + 8);	
			$stations          = explode(" and ", $stationString);	

			if(strstr($stations[0], "-")) 
			{
				$startStation = trim(str_replace("-", "-", $stations[0])); //make it to be same style of name for station
			} 
			else 
			{
				$startStation = trim($stations[0]);
			}
			if(strpos($stations[1], "[") > 0)
			{
				$endStation = trim(substr($stations[1], 0, strpos($stations[1], "["))); // This should overpower the loop
			} 
			else if(strstr($stations[1], "-")) 
			{
				$endStation = trim(str_replace("-", "-", $stations[1]));
			} 
			else 
			{
				$endStation = trim($stations[1]);
			}
			//get the station name

			$stationOrder1 = $this->getStationWithOrder('['.$trainLine.']', $startStation); // Returns array line_id, station_id, station_order
			$stationOrder2 = $this->getStationWithOrder('['.$trainLine.']', $endStation);

			$startStation = $stationOrder1['station_order'];
			$endStation = $stationOrder2['station_order'];
			$this->insertToLineInfo( $stationOrder1['line_id'], $startStation, $endStation, NULL, NULL, NULL, 3, $filename);

			return array('trainLine' => $trainLine, 'boundStation' => NULL, 'startStation' => $startStation, 'endStation' => $endStation, 'changeSummary' => $change, 'changeDetail' => $changeDetail, 'service_replace_id' => 3);
		}
		else if(strpos($change, 'run express') > 0 || strpos($change, 'run local') > 0) // Runs Express/Local
		{
			$stationString     = substr($change, strpos($change, 'from ') + 5);	
			$stations          = explode(" to ", $stationString);
			if(strpos($change, 'from ') > 0)
			{
				$stationString     = substr($change, strpos($change, 'from ') + 5);
				$stations          = explode(" to ", $stationString);
			}
			else
			{
				$stationString     = substr($change, strpos($change, 'between ') + 8);
				$stations          = explode(" and ", $stationString);
			}
			// uptown downtown determination
			$startIndex        = strpos($change, ' ');
			$endIndex          = strpos($change, '-');
			if(strstr($change, "bound"))
			{
				$boundStation      = substr($change, $startIndex, $endIndex - $startIndex);
				$boundStationOrder = $this->getStationWithOrder('['.$trainLine.']', trim($boundStation));
			}
			else
			{
				$boundStation = NULL; // This means both directions
				$boundStationOrder = NULL;
			}

			// get the station name
			if(strstr($stations[0], "-")) {
				$startStation = trim(str_replace("-", "-", $stations[0])); //make it to be same style of name for station
			} else {
				$startStation = trim($stations[0]);
			}
			if(strpos($stations[1], "[") > 0) {
				$endStation = trim(substr($stations[1], 0, strpos($stations[1], "["))); // This should overpower the loop
			} else if(strstr($stations[1], "-")) {
				$endStation = trim(str_replace("-", "-", $stations[1]));
			} else {
				$endStation = trim($stations[1]);
			}

			/*
			if($boundStationOrder['station_order'] > 1)
			{
				//echo "DOWNTOWN";// Going downtown
			}
			else
			{
				//echo "UPTOWN";// Going uptown
			}
			*/

			if(strpos($change, 'run express') > 0) // Service change runs express
			{
				$stationString = substr($change, strpos($change, 'from ') + 5);
				$stations      = explode(" to ", $stationString);
				
				$stationOrder1 = $this->getStationWithOrder('['.$trainLine.']', $startStation); // Returns array line_id, station_id, station_order
				$stationOrder2 = $this->getStationWithOrder('['.$trainLine.']', $endStation);

				$boundStation = $boundStationOrder['station_order'];
				$startStation = $stationOrder1['station_order'];
				$endStation = $stationOrder2['station_order'];
				$this->insertToLineInfo( $stationOrder1['line_id'], $startStation, $endStation, $boundStation, NULL, NULL, 0, $filename);
				return array('trainLine' => $trainLine, 'boundStation' => $boundStation, 'startStation' => $startStation, 'endStation' => $endStation, 'changeSummary' => $change, 'changeDetail' => $changeDetail, 'service_replace_id' => 0);
			}
			else if(strpos($change, 'run local') > 0)
			{
				$stationString = substr($change, strpos($change, 'from ') + 5);
				$stations      = explode(" to ", $stationString);
				
				$stationOrder1 = $this->getStationWithOrder('['.$trainLine.']', $startStation); // Returns array line_id, station_id, station_order
				$stationOrder2 = $this->getStationWithOrder('['.$trainLine.']', $endStation);

				$boundStation = $boundStationOrder['station_order'];
				$startStation = $stationOrder1['station_order'];
				$endStation = $stationOrder2['station_order'];
				$this->insertToLineInfo( $stationOrder1['line_id'], $startStation, $endStation, $boundStation, NULL, NULL, 1, $filename);
				return array('trainLine' => $trainLine, 'boundStation' => $boundStation, 'startStation' => $startStation, 'endStation' => $endStation, 'changeSummary' => $change, 'changeDetail' => $changeDetail, 'service_replace_id' => 1);
			}
		}
		// else {}
	}


	public function findTrain($change)
	{
		$firstBracket = strpos($change, '[');
		$lastBracket  = strpos($change, ']');
		$train = substr($change, $firstBracket + 1, $lastBracket - 2);
		return $train;
	}

	public function insertData($data) {
		// Insert the processed feed into the database.
		$query2 = DB::select()->from('line_info')
		 	->where('line_id', '=', $data[0]) 
		    ->where('start_station_id', '=', $data[1])
		    ->where('end_station_id', '=', $data[2])
		    ->where('start_time', '=', $data[3])
		    ->where('end_time', '=', $data[4])
		    ->where('service_replace_id', '=', $data[5])
		    ->where('filename', '=', $data[6])
		    ->execute()->as_array();

		if( count($query2) == 0 )
		{ 
			$query = DB::insert ('line_info', array('line_id', 'start_station_id','end_station_id','start_time','end_time','service_replace_id','filename'))
			->values(array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6]))->execute();
		}

	}
	
	public function parse_line_name($line_name)
	{
		preg_match_all('/\[([^\]]+)\]/', $line_name, $matches); 
		// return $matches[1][0]; 
		return $matches[1][0];
	}

	public function getStationWithOrder($line_name = NULL, $station_name = NULL )
	// returns an array ( [line_id] , [station_id] , [station_order] ); 
	{
		$station_id = NULL; $line_id = NULL; $station_order = NULL; 
		$line_name_parsed = $this->parse_line_name($line_name);
		$result = 
		DB::select('line_id')->from('line_train')->where('line_bullet', '=', $line_name_parsed)->execute()->as_array();//[0]['line_id']; 

		if(count($result) != 0)
		{
			$line_id = $result[0]["line_id"];
		}

		if($station_name == NULL)
		{
			return array( "line_id" => $line_id ); 
		}

		if(isset($station_name))
		{

			$transform = array( "/" , "-" ); 
			$result = DB::select('*')->from('station')->join('station_order')->on('station.station_id', '=', 'station_order.station_id')
			->where('station_name', 'like', "%". str_replace( $transform , "%", $station_name )."%")
			->where('line_id', '=', $line_id)->execute()->as_array(); 

			$station_order = NULL; 
			$station_id = NULL;  
			if( count($result) == 1 )
			{
				$station_id = $result[0]['station_id']; 
				$station_order = $result[0]['order_number'];  
			}
			else if( count($result) != 0 && $station_name != "36 St")
			{
				//echo "Multiple Result Error (rowcount = ".count($result).") <br />";
				//print_r($result); echo "<br />"; 
			}
			else if( $station_name == "36 St" && count($result) == 2 )
			{
				if( abs( 33 - $result[0]["order_number"] ) < abs( 33 - $result[1]["order_number"] ) )
				{	
					$station_order = $result[0]["order_number"]; 
				}
				else{
				$station_order = $result[1]["order_number"];
				} 
			}
			else
			{
				//echo "Missing Stuff for Station: '$station_name' on Line_ID: '$line_id' for Line_Name: $line_name. ";   
			}
		}
		return array( "line_id" => $line_id, "station_id" => $station_id , "station_order" => $station_order ); 
	}

	// public function insertToLineTrain( $line_id, $start_station )
	// {

	// }


	public function insertToLineInfoArray( $line_id, $affected_stations, $bound_station_id, $start_time, $end_time, $service_replace_id, $filename )
// line_id => integer, affected_station => station_id_list, end_station -> integer,  bound_station_id => direction(>1 for downtown, else for uptown), 
// start_time => integer (unixtimestamp) , end_time => integer (unixtimestamp),
// service_replace_id => real (double) , filename => text		

// SERVICE_REPLACE_ID CODE:
// No trains running : 2
// No trains between A & B : 3
// Trains run express from A to B : 0
// Trains run local from A to B: 1
// Trains skip {stations} : 4 (NOT DONE)
	{
		foreach( $affected_stations as $affected )
		{
			if( !$this->insertToLineInfo( $line_id, $affected, $affected, $bound_station_id, $start_time, $end_time, $service_replace_id, $filename ) )
			{
				return false; 
			} 
		}
		return true; 
	}

	public function insertToLineInfo( $line_id, $start_station, $end_station, $bound_station_id, $start_time, 
		$end_time, $service_replace_id, $filename)
// line_id => integer, start_station => integer, end_station -> integer, bound_station_id => direction(>1 for downtown, else for uptown),
// start_time => integer (unixtimestamp) , end_time => integer (unixtimestamp),
// service_replace_id => real (double) , filename => text	

// SERVICE_REPLACE_ID CODE:
// No trains running : 2
// No trains between A & B : 3
// Trains run express from A to B : 0
// Trains run local from A to B: 1
// Trains skip {stations} : 4

	// returns a true or false, true if works, false, else. 
	{
		$result = DB::select('*')->from('line_info')
		->where('line_id', "=", $line_id)
		->where('start_station_id', "=", $start_station)
		->where('end_station_id', "=", $end_station)
		->where('bound_station_id', "=", $bound_station_id)
		->where('start_time', "=", $start_time)
		->where('end_time', "=", $end_time)
		->where('service_replace_id', "=", $service_replace_id)
		->where('filename', "=", $filename)
		->execute()->as_array();

		$result2 = DB::select( 'id' )->from('line_info')->execute()->as_array(); // check if the table has values; 

		// var_dump( empty($result) ); 
		if( !empty($result) && !empty($result2) )	// does the record exists
		{
				return true; 
		}

		$query = DB::insert('line_info', array( "line_id" , "start_station_id", "end_station_id" , "start_time" , "end_time", "service_replace_id", 
			"filename", "bound_station_id" )
		)->values( array( $line_id, $start_station, $end_station, $start_time, $end_time, $service_replace_id, $filename, $bound_station_id ) )->execute(); 
			return true; 
	}


	public function getServiceChange($filename)
	{
		$result = DB::select ('line_id','start_station_id','end_station_id','bound_station_id','service_replace_id','filename') ->from('line_info') ->where('filename','=', $filename)
		->execute()->as_array();
		$i=0;
		// print_r($result); 
		foreach ($result as $res) {
			$line_id = $res['line_id'];
			$start_station = $res['start_station_id'];
			$end_station = $res['end_station_id'];
			$bound = $res['bound_station_id'];
			$service = $res['service_replace_id'];
			$theFile= $res['filename'];
		$array1[$i]= array('line_id' => $line_id, 'start_station_id' => $start_station, 'end_station_id' => $end_station, 'bound_station_id' => $bound, 'service_replace_id' => $service, 'filename' => $theFile);
		$i++;
		}
		if(!isset($array1))
		{
			$query = DB::insert('line_info', array( "line_id" , "start_station_id", "end_station_id" , "start_time" , "end_time", "service_replace_id", 
				"filename", "bound_station_id" )
			)->values( array( 500, NULL, NULL, NULL, NULL, 0, $filename, NULL ) )->execute();
			return $this->getServiceChange($filename);
		}
		return $array1;
	}

	public function getStationNameforStationOrder($lineID, $stationOrder){

		$name = DB::	select('station_name') 
		->from('station')->join('station_order')->on('station.station_id', '=', 'station_order.station_id') ->where ('line_id','=',$lineID) ->where('order_number','=',$stationOrder)
		->execute()->as_array();

		return $name;
	}
 }
