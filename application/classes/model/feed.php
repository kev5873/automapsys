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

}