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

		

		//echo "something <br/>";
		//echo $otherTrain;
		var_dump($returnArray);
		$size = sizeof($returnArray);
		for ($i =0; $i < sizeof($returnArray);$i++){
			//the train line doesnt display correctly. 
				$trainLetter = $line->getLineBullet($id);						
			if(isset($returnArray[$i]['trainLine'])){
				$otherTrain = $returnArray[$i]['trainLine'];

				echo $trainLetter;
				echo $otherTrain;
				echo $size;
				echo $i;
			}

			if(isset($otherTrain) && ($otherTrain==$trainLetter))

			{	
				$the = $returnArray[$i]['trainLine'].'<br/> '.$returnArray[$i]['boundStation'].' Bounds. <br/> Start: '.$returnArray[$i]['startStation'].'<br/> End: '.$returnArray[$i]['endStation'].'<br/> Service Changed: '.$returnArray[$i]['changeSummary'].'<br/> ';
		 		$this->template->advisory ='<br/>'. $the;
			}

			else if($i==($size-1)){
				$this->template->advisory = "<br/>Good Service";	
				}
					
			}	

		}
		
	}

