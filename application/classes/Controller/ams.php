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
        $returnArray = $feeder->getServiceChange('a/s/status-1366515360.xml');

        // // echo 'a'; 
        // echo count($returnArray); 
        // print_r($returnArray); 

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

		$this->template->lineData = $line->grabStations($id,$direction,'a/s/status-1366515360.xml');
		$this->template->line = $id;
		$this->template->routeDesignation = $line->getLineBullet($id);
		$this->template->routeDetail = $line->getLineDescription($id);
		$this->template->direction = ucfirst($direction);
		//$this->template->advisory = "TEST";
		

		//echo "something <br/>";
				//need to determine the correct train output

		var_dump($returnArray);
		$size = sizeof($returnArray);
		echo "<br/>";
		echo $size;
		$lineID = $line->getLineBullet($id);
		//echo $lineID."  first train ID ";
		$i=0;
		$otherlineId = 'E';

		$j = 1;$z=0;
		$otherlineId1[0]='';
		while($j<=$size ){

			if(isset($returnArray[$j]['line_id'])) {
				$otherlineId1[$z] = $returnArray[$j]['line_id'];

				$otherArr[$z]=$returnArray[$j];
				$z++;
			}	
			$j++;

		}
		$sizeofLine = sizeof($otherlineId1);


		 while($i<$size ){
				//echo $i;
			if($returnArray[$i]['line_id']!="")
			{
				$otherlineId = $returnArray[$i]['line_id'];
				//$line->getLineBullet($id);
				$otherlineIdLetter= $line->getLineBullet($otherlineId);
				//echo $i;
				//echo  $otherlineId;
				echo $otherlineIdLetter;
				echo $lineID;

				if($lineID == $otherlineIdLetter)
				{
					 $this->template->advisory ='<br/>'.$returnArray[$i]['line_id'].'<br/> Direction:'.$returnArray[$i]['bound_station_id'].' Bound <br/> Start: '
					 .$returnArray[$i]['start_station_id'].'<br/> End: '.$returnArray[$i]['end_station_id'].'<br/> Service Chg: '
					 ;
					//$this->template->advisory = $returnArray[$i]['line_id'];
					break;
				}
				else{
					$this->template->advisory = '<br/>Good Service';
					}
			}
			else{}
			
			$i++;
		}

		$this->template->status1= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status2= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status3= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status4= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status5= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status6= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status7= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status8= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status9= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status10= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status11= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status12= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status13= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status14= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status15= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status16= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status17= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status18= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status19= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status20= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status21= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status22= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status23= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status24= "<img src='a/i/checkmark.png' class='miniC'/>";
		$this->template->status25= "<img src='a/i/checkmark.png' class='miniC'/>";

		for($k=0;$k< $sizeofLine;$k++)
		{
			$lineStatus = $otherlineId1[$k];
			//echo $lineStatus;
			$otherlineIdLetter= $line->getLineBullet($lineStatus);


			switch($otherlineIdLetter)
			{
				case '1':$this->template->status1= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '2':$this->template->status2= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '3':$this->template->status3= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '4':$this->template->status4= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '5':$this->template->status5= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '6':$this->template->status6= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '7':$this->template->status7= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '6D':$this->template->status8= "<img src='a/i/caution.png' class='miniC'/>";break;
				case '7D':$this->template->status9= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'A':$this->template->status10= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'C':$this->template->status11= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'E':$this->template->status12= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'B':$this->template->status13= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'D':$this->template->status14= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'F':$this->template->status15= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'M':$this->template->status16= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'Q':$this->template->status17= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'N':$this->template->status18= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'R':$this->template->status19= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'G':$this->template->status20= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'L':$this->template->status21= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'J':$this->template->status22= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'Z':$this->template->status23= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'S':$this->template->status24= "<img src='a/i/caution.png' class='miniC'/>";break;
				case 'S':$this->template->status25= "<img src='a/i/caution.png' class='miniC'/>"; break;


			}
		}
	}	

}
		

