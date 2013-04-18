<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public function downloadFeed() {
		$url = 'http://www.mta.info/status/serviceStatus.txt';
		$currentTime = time();
		$path = getcwd()."/a/s/status-".$currentTime.".xml";
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
		return "a/s/status-".$currentTime.".xml";
	}

	public function processFeed($file) {
		$subway = simplexml_load_file($file);

		$output = array();
		for($i = 0; $i < 11; $i++)
		{
			$singleTrunkLine = new DOMDocument();
			@$singleTrunkLine->loadHTML($subway->subway[0]->line[$i]->text[0]);
			$trunkLineAdvisory = $singleTrunkLine->getElementsByTagName('a');
			$trunkLineDetail = $singleTrunkLine->getElementsByTagName('div');

			$changes = array();
			$changeDetail = array();
			$c1 = 0;
			$c2 = 0;

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

		var_dump($output);
		return $output; 

		// Example Usage $output[trunkID][change/changeDetail][route(depends on # of advisories)]
		echo $output[3]['change'][0] . '<br />';
		echo $output[3]['changeDetail'][0];
	}

	public function processIndividual($string)
	{
		// Check documentation
	}

	public function insertData($data) {
		// Insert the processed feed into the database.
	}
	
	public function parse_line_name($line_name)
	{
		preg_match_all('/\[([^\]]+)\]/', $line_name, $matches); 
		// return $matches[1][0]; 
		return $matches[1][0];
	}

	public function getStationStuff($line_name = NULL, $station_name = NULL)
	{

		$station_id = NULL; $line_id = NULL; $station_order = NULL; 
		$line_name_parsed = $this->parse_line_name($line_name); 	
		$result = 
		DB::select('line_id')->from('line_train')->where('line_bullet', '=', $line_name_parsed)->execute()->as_array()[0]['line_id']; 

		if( count($result) != 0 )
		{
			$line_id = $result[0];
		}

		if($station_name == NULL)
		{
			return array( "line_id" => $line_id ); 
		}

		if(isset($station_name))
		{
		$result = DB::select('station_id')->from('station')->where('station_name', 'like', '%'.$station_name.'%')->execute()->as_array(); 

		if( count($result) )
		{
			$station_id = $result[0]['station_id']; 

		$result = DB::select('order_number')
				->from('station_order')
				->where('line_id', '=', $line_id)
				->where('station_id', '=' , $station_id)
				->execute()->as_array();

		$station_order = $result[0]['order_number']; 		
		}

		
		}

		return array( "line_id" => $line_id, "station_id" => $station_id , "station_order" => $station_order ); 
	
	}
}