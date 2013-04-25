<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public function downloadFeed()
	{
		$url         = 'http://www.mta.info/status/serviceStatus.txt';
		$currentTime = time();
		$path        = gtecwd()."/a/s/status-".$currentTime.".xml";
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

		//var_dump($output);
		//die();

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
						 $retArr[$i]=$this->processIndividual($aChange, $bChange);
					}
				}
			}
		}

		return $retArr;
		/*
		$aChange = $output[3]['change'][0] . '<br />';
		$bChange = $output[3]['changeDetail'][0];
		//$aChange = $output[0]['change'][0] . '<br />';
		//$bChange = $output[0]['changeDetail'][0];
		$this->processIndividual($aChange, $bChange);
		*/

		//return $output;

		// Example Usage $output[trunkID][change/changeDetail][route(depends on # of advisories)]
		//echo $output[3]['change'][0] . '<br />';
		//echo $output[3]['changeDetail'][0];
	}

	public function processIndividual($change, $changeDetail)
	{	
		$change            = strip_tags($change);
		$changeDetail      = strip_tags($changeDetail);
		$trainLine         = $this->findTrain($change);

		if(strpos($change, 'run express') > 0 || strpos($change, 'run local') > 0)
		{
			$stationString     = substr($change, strpos($change, 'from ') + 5);
			$stations          = explode(" to ", $stationString);
			
			// uptown downtown determination
			$startIndex        = strpos($change, ' ');
			$endIndex          = strpos($change, '-');
			$boundStation      = substr($change, $startIndex, $endIndex - $startIndex);
			$boundStationOrder = $this->getStationWithOrder('['.$trainLine.']', trim($boundStation));

			// get the station name
			if(strstr($stations[0], "-")) {
				$startStation = trim(str_replace("-", " - ", $stations[0])); //make it to be same style of name for station
			} else {
				$startStation = trim($stations[0]);
			}
			if(strpos($stations[1], "[") > 0) {
				$endStation = trim(substr($stations[1], 0, strpos($stations[1], "["))); // This should overpower the loop
			} else if(strstr($stations[1], "-")) {
				$endStation = trim(str_replace("-", " - ", $stations[1]));
			} else {
				$endStation = trim($stations[1]);
			}

			if($boundStationOrder['station_order'] > 1)
			{
				echo "DOWNTOWN";// Going downtown
			}
			else
			{
				echo "UPTOWN";// Going uptown
			}

			echo $boundStation . '-bound<br />';

			if(strpos($change, 'run express') > 0) // Service change runs express
			{
				$stationString = substr($change, strpos($change, 'from ') + 5);
				$stations      = explode(" to ", $stationString);
				
				$stationOrder1 = $this->getStationWithOrder('['.$trainLine.']', $startStation); // Returns array line_id, station_id, station_order
				$stationOrder2 = $this->getStationWithOrder('['.$trainLine.']', $endStation);

				echo $trainLine . ' Trains run express' . '<br />';
				echo $boundStation . ' : ' . $boundStationOrder['station_order'] . '<br />';
				echo $startStation . ' : ' . $stationOrder1['station_order'] . '<br />';
				echo $endStation . ' : ' . $stationOrder2['station_order'] . '<br />';

				return array('trainLine' => $trainLine, 'boundStation' => $boundStation, 'startStation' => $startStation, 'endStation' => $endStation, 'changeSummary' => $change, 'changeDetail' => $changeDetail);
				// INSERT STUFF INTO THE DATABASE
			}
			else if(strpos($change, 'run local') > 0)
			{
				$stationString = substr($change, strpos($change, 'from ') + 5);
				$stations      = explode(" to ", $stationString);
				
				$stationOrder1 = $this->getStationWithOrder('['.$trainLine.']', $startStation); // Returns array line_id, station_id, station_order
				$stationOrder2 = $this->getStationWithOrder('['.$trainLine.']', $endStation);

				echo $trainLine . ' Trains run local' . '<br />';
				echo $boundStation . ' : ' . $boundStationOrder['station_order'] . '<br />';
				echo $startStation . ' : ' . $stationOrder1['station_order'] . '<br />';
				echo $endStation . ' : ' . $stationOrder2['station_order'] . '<br />';

				return array('trainLine' => $trainLine, 'boundStation' => $boundStation, 'startStation' => $startStation, 'endStation' => $endStation, 'changeSummary' => $change, 'changeDetail' => $changeDetail);
			}
			else
			{

			}
			echo '<br />';
		}
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

	public function getStationWithOrder($line_name = NULL, $station_name = NULL)
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
			else if( count($result) != 0 )
			{
				echo 'Multiple Result Error';
			}
			else
			{
				echo "Missing Stuff for Station: $station_name on Line: $line_id ";  
			}

			// $result = DB::select('station_id')->from('station')->where('station_name', 'like', '%'.$station_name.'%')->execute()->as_array();
			// print_r($result); 
			// if( count($result) != 0)
			// {
			// 	$station_id = $result[0]['station_id']; 

			// 	echo "StationID:".$station_id." for $station_name on $line_id to $line_name_parsed ;"; 
			// 	// print_r($line_id);  

			// 	$result = DB::select('order_number')
			// 		->from('station_order')
			// 		->where('line_id', '=', $line_id)
			// 		->where('station_id', '=' , $station_id)
			// 		->execute()->as_array();

			// 	if( isset($result[0]) )
			// 	{
			// 	$station_order = $result[0]['order_number']; 		
			// 	}
			// 	else
			// 	{
			// 		print_r($result); 
			// 	}
			// }

		}
		return array( "line_id" => $line_id, "station_id" => $station_id , "station_order" => $station_order ); 
	}



 }
