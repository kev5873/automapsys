<?php defined('SYSPATH') or die('No direct script access.');

class Model_line extends Model
{
	public function grabStations($line,$direction)
	{

		$theLine = DB::select()
				->from('line_train')
				->where('line_id', '=', $line)
				->execute()->as_array();
		//echo $theLine[0]['line_bullet'] . ' - ' . $theLine[0]['line_name'] . '<br />';

		
		if($direction =="downtown"||$direction =="DOWNTOWN")
		{
			//echo "in the range, for testing!";
			$stations = DB::select('station_id','order_number')
				->from('station_order')
				->where('line_id', '=', $line)
				->order_by('order_number','desc')
				->execute()
				->as_array();
		}else
		{
			$stations = DB::select()
				->from('station_order')
				->where('line_id', '=', $line)
				->order_by('order_number','asc')
				->execute()
				->as_array();
		}



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
		$numOfStation = sizeof($stations)-1;

		//echo $numOfStation."<br/>";

		for($i=$numOfStation;$i>=0;$i--)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			//if($calculatingParsedData)
			//{

			//}
			//else //use defeault below

			$currentStationOrderID = $stations[$i]['order_number'];

			$color='black';
			switch($line)
			{
				case 1:
					$color = 'red';
					if($currentStationOrderID == 36)
						$singleStation = $singleStation->where('express', '=', 'true');
					break;

				case 2:
					$color = 'red';
					if($currentStationOrderID > 24 && $currentStationOrderID <41)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 3:
					$color = 'red';
					if($currentStationOrderID > 6 && $currentStationOrderID < 23)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;
				
				case 4:
					$color = 'green';
					if(($currentStationOrderID == 13) || ($currentStationOrderID > 14 && $currentStationOrderID < 34) || ($currentStationOrderID > 39 && $currentStationOrderID<= 54) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}

					break;

				case 5:
					$color = 'green';
					

					if($currentStationOrderID > 15 &&  $currentStationOrderID< 34 )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					
					break;

				case 6:
					$color = 'green';
					break;

				case 7:
					$color = 'purple';
					break;

				case 8:
					$color = 'green';
					break;

				case 9: 
					$color = 'purple';
					break;

				case 10:
					$color = 'blue';
					if(($currentStationOrderID > 5 && $currentStationOrderID <27) || ($currentStationOrderID > 31 && $currentStationOrderID <44))
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 11:
					$color = 'blue';
					break;

				case 12:
					$color = 'blue';
					if(($currentStationOrderID > 3 && $currentStationOrderID <20) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;					
				
				case 13:
					$color = 'orange';
					break;

				case 14:
					$color = 'orange';
					if(($currentStationOrderID == 24) )
					{
						$singleStation = $singleStation->where('express', '=', 'false');
					}
					if(($currentStationOrderID > 25 && $currentStationOrderID <30) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 15: 
					$color = 'orange';
					break;

				case 16:
					$color = 'orange';
					break;

				case 17:
					$color = 'lemon_yellow';
					break;

				case 18:
					$color = 'lemon_yellow';
					if(($currentStationOrderID >19 &&$currentStationOrderID< 25) )
					{
						$singleStation = $singleStation->where('express', '=', 'false');
					}
					if(($currentStationOrderID == 25 ||$currentStationOrderID == 26) )
					{
						$singleStation = $singleStation->where('express', '=', 'false');
					}
					if(($currentStationOrderID >27 && $currentStationOrderID <35) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}

					break;

				case 19:
					$color = 'lemon_yellow';
					break;

				case 20:
					$color = 'lime';
					break;

				case 21:
					$color = 'light gray';
					break;

				case 22:
					$color = 'brown';
					break;

				case 23:
					$color = 'brown';
					break;

				case 24:
					$color = 'light gray';
					break;

				case 25:
					$color = 'light gray';
					break;







			}
			$singleStation = $singleStation->execute()->as_array();
					
			if(isset($singleStation[0]))
				$returnString = $returnString . $this->generateTableRow($color,$singleStation[0]['station_name'],$singleStation[0]['station_id'],$i);

		}
/*
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
						$returnString = $returnString . $this->generateTableRow('red',$singleStation[0]['station_name'],$singleStation[0]['station_id'],$i);
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
					->where('station_id', '=', $stations[$i]['station_id']);
					
					if($i > 15 && $i < 34)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}

					$singleStation = $singleStation->execute()->as_array();
					
					if(isset($singleStation[0]))
						$returnString = $returnString . $this->generateTableRow('green',$singleStation[0]['station_name'],$singleStation[0]['station_id'],$i);
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
						$returnString = $returnString . $this->generateTableRow('black',$singleStation[0]['station_name'],$singleStation[0]['station_id'],$i);
	
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

	public function grabStationsRaw($line,$direction)
	{
		$theLine = DB::select()
				->from('line_train')
				->where('line_id', '=', $line)
				->execute()->as_array();
		//echo $theLine[0]['line_bullet'] . ' - ' . $theLine[0]['line_name'] . '<br />';

		$stations = DB::select()
				->from('station_order')
				->where('line_id', '=', $line)
				->order_by('order_number', 'DESC')
				->execute()
				->as_array();

		if($direction =="uptown"||$direction =="UPTOWN")
		{
			//echo "in the range, for testing!";
			
			for($i=0;$i<sizeof($stations);$i++)
			{
				$newStat[$i]=$stations[sizeof($stations)-1-$i];
			}

			$stations = $newStat;
		}
		$returnArray = array();

		for($i=0;$i<sizeof($stations);$i++)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			//if($calculatingParsedData)
			//{

			//}
			//else //use defeault below
			$color='black';
			switch($line)
			{
				case 1:
					$color = 'red';
					if($i == sizeof($stations)-4)
						$i++;
					break;

				case 2:
					$color = 'red';
					if($i > 24 && $i <41)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 3:
					$color = 'red';
					if($i > 6 && $i < 23)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;
				
				case 4:
					$color = 'green';
					if(($i == 13) || ($i > 14 && $i < 33) || ($i > 39 && $i < 54) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}

					break;

				case 5:
					$color = 'green';
					if($i > 15 && $i < 34 )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					
					break;

				case 6:
					$color = 'green';
					break;

				case 7:
					$color = 'purple';
					break;

				case 8:
					$color = 'green';
					break;

				case 9: 
					$color = 'purple';
					break;

				case 10:
					$color = 'blue';
					if(($i > 5 && $i <26) || ($i > 31 && $i <44))
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 11:
					$color = 'blue';
					break;

				case 12:
					$color = 'blue';
					break;					
				
				case 13:
					$color = 'blue';
					break;

				case 14:
					$color = 'blue';
					break;

				case 15:
					$color = 'blue';
					break;

				case 16:
					$color = 'blue';
					break;

				case 17:
					$color = 'blue';
					break;

				case 18:
					$color = 'blue';
					break;

				case 19:
					$color = 'blue';
					break;

				case 20:
					$color = 'blue';
					break;

				case 21:
					$color = 'blue';
					break;

				case 22:
					$color = 'blue';
					break;

				case 23:
					$color = 'blue';
					break;

				case 24:
					$color = 'blue';
					break;

				case 25:
					$color = 'blue';
					break;
			}
			$singleStation = $singleStation->execute()->as_array();
					
			if(isset($singleStation[0]))
				array_push($returnArray,$singleStation[0]);

		}
		return $returnArray;
		
	}

	public function generateTableRow($color, $station_name, $station_id, $order_number)
	{
		switch($color)
		{
			case 'red':
				$hexColor = 'ff3333';
				break;
			case 'green':
				$hexColor = '009933';
				break;
			case 'purple':
				$hexColor = 'cc3399';
				break;
			case 'blue':
				$hexColor = '2850ad';
				break;
			case 'orange':
				$hexColor = 'ff6319';
				break;
			case 'lime':
				$hexColor = '6cbe45';
				break;
			case 'brown':
				$hexColor = '996633';
				break;
			case 'light gray':
				$hexColor = 'a7a9ac';
				break;
			case 'dark gray':
				$hexColor = '808183';
				break;
			case 'yellow':
				$hexColor = 'fccc0a';
			case 'lemon_yellow':
				$hexColor = 'FCF141';
				break;
			case 'brown':
				$hexColor = '7F5417';
				break;


			default:
				$hexColor = '000000';
				break;


		}
		return '<tr>
		<td style="background-color: #'.$hexColor.'; padding: 15px 0px 15px 0px;">
		<img src="'.URL::base().'a/i/stationstop16px.png" />
		</td> <td style="padding-left: 15px;">'.$station_name." : ".$station_id. " - " . $order_number .'</td>
		</tr>';
	}

	public function getLineBullet($line)
	{
		$theLine = DB::select()
			->from('line_train')
			->where('line_id', '=', $line)
			->execute()->as_array();
		return $theLine[0]['line_bullet'];
	}

	public function getLineDescription($line)
	{
		$theLine = DB::select()
			->from('line_train')
			->where('line_id', '=', $line)
			->execute()->as_array();
		return $theLine[0]['line_name'];
	}
}