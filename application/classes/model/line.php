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



		
		$returnString ='';
		$numOfStation = sizeof($stations)-1;

		//echo $numOfStation."<br/>";

		for($i=$numOfStation;$i>=0;$i--)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			
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

		return $returnString;
		
	}

	public function grabStationsRaw($line,$direction)
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



		
		$returnArray = array();
		$numOfStation = sizeof($stations)-1;

		//echo $numOfStation."<br/>";

		for($i=$numOfStation;$i>=0;$i--)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			
			$currentStationOrderID = $stations[$i]['order_number'];

			$color='black';
			switch($line)
			{
				case 1:
					$color = 'ff3333';
					if($currentStationOrderID == 36)
						$singleStation = $singleStation->where('express', '=', 'true');
					break;

				case 2:
					$color = 'ff3333';
					if($currentStationOrderID > 24 && $currentStationOrderID <41)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 3:
					$color = 'ff3333';
					if($currentStationOrderID > 6 && $currentStationOrderID < 23)
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;
				
				case 4:
					$color = '009933';
					if(($currentStationOrderID == 13) || ($currentStationOrderID > 14 && $currentStationOrderID < 34) || ($currentStationOrderID > 39 && $currentStationOrderID<= 54) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}

					break;

				case 5:
					$color = '009933';
					

					if($currentStationOrderID > 15 &&  $currentStationOrderID< 34 )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					
					break;

				case 6:
					$color = '009933';
					break;

				case 7:
					$color = 'cc3399';
					break;

				case 8:
					$color = '009933';
					break;

				case 9: 
					$color = 'cc3399';
					break;

				case 10:
					$color = '2850ad';
					if(($currentStationOrderID > 5 && $currentStationOrderID <27) || ($currentStationOrderID > 31 && $currentStationOrderID <44))
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 11:
					$color = '2850ad';
					break;

				case 12:
					$color = '2850ad';
					if(($currentStationOrderID > 3 && $currentStationOrderID <20) )
					{
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;					
				
				case 13:
					$color = 'ff6319';
					break;

				case 14:
					$color = 'ff6319';
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
					$color = 'ff6319';
					break;

				case 16:
					$color = 'ff6319';
					break;

				case 17:
					$color = 'FCF141';
					break;

				case 18:
					$color = 'FCF141';
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
					$color = 'FCF141';
					break;

				case 20:
					$color = '6cbe45';
					break;

				case 21:
					$color = 'a7a9ac';
					break;

				case 22:
					$color = '996633';
					break;

				case 23:
					$color = '996633';
					break;

				case 24:
					$color = 'a7a9ac';
					break;

				case 25:
					$color = 'a7a9ac';
					break;
			}
			$singleStation = $singleStation->execute()->as_array();

			if(isset($singleStation[0]))
			{
				$singleStation[0]['color'] = $color;
				array_push($returnArray,$singleStation[0]);
			}

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