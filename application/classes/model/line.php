<?php defined('SYSPATH') or die('No direct script access.');

class Model_line extends Model
{
	public function grabStations($line)
	{

		$theLine = DB::select()
				->from('line_train')
				->where('line_id', '=', $line)
				->execute()->as_array();
		echo $theLine[0]['line_bullet'] . ' - ' . $theLine[0]['line_name'] . '<br />';

		$stations = DB::select()
				->from('station_order')
				->where('line_id', '=', $line)
				->execute()
				->as_array();

		/*

		$j = 0;
 
		foreach ($stations as $station)
		{
		 $singleStation = DB::select()
		  ->from('station')
		  ->where('station_id', '=', $station['station_id'])
		  //->where('express', '=', true)
		  ->execute()->as_array();
		 //var_dump($singleStation);
		  if(isset($singleStation[0]))
		   //echo $station['order_number'] . ' - ' .$singleStation[0]['station_name']  . '<br />';
		   {
		    $stationArr[$j]=$singleStation[0];
		   }
		  $j++;
		}
		
		for($i =0; $i<sizeof($stationArr);$i++)
		{
		 	echo $stationArr[$i]['station_name'];
		}

		*/
		$returnString ='';

		switch($line)
		{
			case 1:
				for($i=0;$i<sizeof($stations);$i++)
				{
					$singleStation = DB::select()
					->from('station')
					->where('station_id', '=', $stations[$i]['station_id'])
					->execute()->as_array();

					if($i==sizeof($stations)-4)
						$i++;

					if(isset($singleStation[0]))
						$returnString = $returnString . '<tr> <td style="background-color: #ff3333; padding: 15px 0px 15px 0px;"><img src="'.URL::base().'a/i/stationstop16px.png" /></td> <td style="padding-left: 15px;">'.$singleStation[0]['station_name']." : ".$singleStation[0]['station_id']. " - " . $i .'</td> </tr>';
					
				}
				break;

			case 4:


			case 5:
				//express based on time
				//if()
				
				//local based on time
				for($i=0;$i<sizeof($stations);$i++)
				{
					$singleStation = DB::select()
					->from('station')
					->where('station_id', '=', $stations[$i]['station_id'])
					->execute()->as_array();
					
					if($i > 15 && $i < 34)
					{
						$singleStation = DB::select()
						->from('station')
						->where('station_id', '=', $stations[$i]['station_id'])
						->where('express', '=', 'true')
						->execute()->as_array();
					}
					

					//	$result = DB::query("foo")->from("table")->join("table2","CROSS")->join("table3"."CROSS");

					if(isset($singleStation[0]))
						$returnString = $returnString . '<tr> <td style="background-color: #ff3333; padding: 15px 0px 15px 0px;"><img src="'.URL::base().'a/i/stationstop16px.png" /></td> <td style="padding-left: 15px;">'.$singleStation[0]['station_name']." : ".$singleStation[0]['station_id']. " - " . $i .'</td> </tr>';
					
				}
				break;

			default:
				for($i=0;$i<sizeof($stations);$i++)
				{
					$singleStation = DB::select()
					->from('station')
					->where('station_id', '=', $stations[$i]['station_id'])
					->execute()->as_array();

					if(isset($singleStation[0]))
						$returnString = $returnString . '<tr> <td style="background-color: #ff3333; padding: 15px 0px 15px 0px;"><img src="'.URL::base().'a/i/stationstop16px.png" /></td> <td style="padding-left: 15px;">'.$singleStation[0]['station_name']." : ".$singleStation[0]['station_id']. " - " . $i .'</td> </tr>';
						
				}
				break;

		}
/*
		for($i=0;$i<sizeof($stations);$i++)
		{
			$singleStation = DB::select()
				->from('station')
				->where('station_id', '=', $stations[$i]['station_id']);
				
				
					->where('express', '=', true)
				->execute()->as_array();

				if(isset($singleStation[0]))
				$returnString = $returnString . '<tr> <td style="background-color: #ff3333; padding: 15px 0px 15px 0px;"><img src="'.URL::base().'a/i/stationstop16px.png" /></td> <td style="padding-left: 15px;">'.$singleStation[0]['station_name']." : ".$singleStation[0]['station_id']. " - " . $i .'</td> </tr>';
		}*/
		return $returnString;
		
	}
}