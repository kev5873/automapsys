<?php defined('SYSPATH') or die('No direct script access.');

class Controller_ams extends Controller_Template {

	public $template = 'site';
	
	public function action_index()
	{
		$this->template->title   = 'MTA New York City Subway Service Advisories';
		$this->template->message = 'hello, world!';

		// This is feeder code, DO NOT DELETE YET!
		//$feeder = new Model_feed();
		//$feeder->processFeed(getcwd()."/a/s/status-1364696060.xml");

		$line = new Model_line();
		$feeder = new Model_feed();
        $returnArray = $feeder->processFeed('a/s/status-1366515360.xml');

		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{
			$id = 12;
		}
		if(isset($_GET['direction']))
		{
			$direction = $_GET['direction'];

		}
		else
		{
			$direction = "Downtown";
		}

		$this->template->lineData = $line->grabStations($id,$direction);
		$this->template->line = $id;
		$this->template->routeDesignation = $line->getLineBullet($id);
		$this->template->routeDetail = $line->getLineDescription($id);
		$this->template->direction = ucfirst($direction);
		//$this->template->advisory = $returnArray;
		

		//echo "something <br/>";
				//need to determine the correct train output

		var_dump($returnArray);
		$size = sizeof($returnArray);
		echo "<br/>";
		echo $size;
		$lineID = $line->getLineBullet($id);
		echo $lineID;
		$i=1;
		$otherlineId = 'E';

		 while($i<=$size ){

			if(isset($returnArray[$i]['trainLine']))
			{
				$otherlineId = $returnArray[$i]['trainLine'];
				echo  $otherlineId;
			}

			if($lineID == $otherlineId)
			{
				$this->template ->advisory ='<br/>'.$returnArray[$i]['trainLine'].'<br/> Direction:'.$returnArray[$i]['boundStation'].' Bound <br/> Start: '
				.$returnArray[$i]['startStation'].'<br/> End: '.$returnArray[$i]['endStation'].'<br/> Service Chg: '
				.$returnArray[$i]['changeSummary'].'<br/>';
				break;

			}
			else{
				$this->template ->advisory = '<br/>Good Service';
				break;	
			}

			$i++;
		}
	}	

}
		

