<?php defined('SYSPATH') or die('No direct script access.');

class Model_line extends Model
{
	public function grabStations($line,$direction,$location)
	{
		// $advisories = DB::select()
		// 		->from('line_info')
		// 		->where('filename', '=', $location) 	 	
		// 		->where()
		// 		->execute()->as_array();

		$direction = strtolower($direction); 

		if( $direction == "downtown"){
		$advisories_query = DB::query( Database::SELECT , "select * from line_info where filename = :filename and line_id = :line_id
			and ( bound_station_id > 1 or bound_station_id is NULL ); " ); }
		else {
			$advisories_query = DB::query( Database::SELECT , "select * from line_info where filename = :filename and line_id = :line_id
			and ( bound_station_id <= 1 or bound_station_id is NULL ); " ); }

		$advisories_query->param( ":filename" , $location); 
		$advisories_query->param( ":line_id" , $line); 

		$advisories = $advisories_query->execute()->as_array(); 


		// SERVICE_REPLACE_ID CODE:
		// No trains running : 2 (Done)
		// No trains between A & B : 3 
		// Trains run express from A to B : 0
		// Trains run local from A to B: 1
		// Trains skip {stations} : 4 (Cant do)

		$trainsNotRunning = array();

		foreach($advisories as $arr)
		{
			// print_r($arr);
			if($arr['service_replace_id'] == 2)
				array_push($trainsNotRunning, $arr['line_id']);
		}

		// print_r($trainsNotRunning);

		if(in_array($line, $trainsNotRunning))
		{
			return '<tr>
			<td style="font-size: 24pt;">Trains are not running.</td>
			</tr>';
		}

		$runsExpress = false;
		$runLocal = false;
		$notrainsbetween = false; 

		if(count($advisories) >0){
			$theReplaceID = $advisories[0]['service_replace_id'];
			if($theReplaceID == 0)
			{
				$runsExpress = true;
			}
			else if($theReplaceID == 1)
			{
				$runLocal = true;
			}
			else if($theReplaceID == 3)	// No Trains Between Case
			{
				// start_station_id = order_number, end_station_id = order_number
				// echo "Has No Train Between Case for ". $advisories[0]['start_station_id'] . ": " . $advisories[0]['end_station_id'] . "on $line"; 
				$notrainsbetween = true; 
				$notrainsbetweenpairarray = array($advisories[0]['start_station_id'] , $advisories[0]['end_station_id'] ); 
			}
		}


		

		// No Trains Between 3:

		// print_r($advisories); 
		$theLine = DB::select()
				->from('line_train')
				->where('line_id', '=', $line)
				->execute()->as_array();

		
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

// SERVICE_REPLACE_ID CODE:
// No trains running : 2
// No trains between A & B : 3
// Trains run express from A to B : 0
// Trains run local from A to B: 1
// Trains skip {stations} : 4

		
		$returnString ='';
		$numOfStation = sizeof($stations)-1;

		//echo $numOfStation."<br/>";

		for($i=$numOfStation;$i>=0;$i--)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			
			$currentStationOrderID = $stations[$i]['order_number'];

			if( $notrainsbetween )
			{
				$start_station_order = $notrainsbetweenpairarray[0]; 
				$end_station_order = $notrainsbetweenpairarray[1];

 				if( ( ($start_station_order <= $currentStationOrderID) && ($currentStationOrderID <= $end_station_order) ) || 
				  	( ( $start_station_order >= $currentStationOrderID ) && ($currentStationOrderID >= $end_station_order ) ) 
				  )
				{
					$singleStation->where('station_id', '=', '-1'); 
				}
			}

			$color='black';
			switch($line)
			{	
				case 1:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else if($currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$runLocal = false;
							}
							
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else if($currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$runLocal = false;
							}
						}

					}
					else if($notrainsbetween)
					{

					}
					else{

					if($currentStationOrderID == 36)
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 2:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}

						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}

						
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
							}
							else
							{
								
							}
						}

					}
					else {
						if($currentStationOrderID > 24 && $currentStationOrderID <41)
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 3:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{


						if($currentStationOrderID > 6 && $currentStationOrderID < 23)
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
						break;
				
				case 4:
					$color = 'green';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else
					{
						if(($currentStationOrderID == 13) || ($currentStationOrderID > 14 && $currentStationOrderID < 34) || ($currentStationOrderID > 39 && $currentStationOrderID<= 54) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}

					break;

				case 5:
					$color = 'green';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
						if($currentStationOrderID > 15 &&  $currentStationOrderID < 34 )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 6:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'green';
					break;

				case 7:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'purple';
					break;

				case 8:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'green';
					break;

				case 9: 
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'purple';
					break;

				case 10:
					$color = 'blue';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
						if(($currentStationOrderID > 5 && $currentStationOrderID <27) || ($currentStationOrderID > 31 && $currentStationOrderID <44))
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 11:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'blue';
					break;

				case 12:
					$color = 'blue';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}else{
						if(($currentStationOrderID > 3 && $currentStationOrderID <20) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;					
				
				case 13:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 14:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else
					{
						if(($currentStationOrderID == 24) )
						{
							$singleStation = $singleStation->where('express', '=', 'false');
						}
						if(($currentStationOrderID > 25 && $currentStationOrderID <30) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 15: 
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 16:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 17:
					$color = 'lemon_yellow';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 18:
					$color = 'lemon_yellow';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
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

					}
					break;

				case 19:
					$color = 'lemon_yellow';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}

					break;

				case 20:
					$color = 'lime';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 21:
					$color = 'light gray';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 22:
					$color = 'brown';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 23:
					$color = 'brown';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 24:
					$color = 'light gray';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					break;

				case 25:
					$color = 'light gray';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
							else
							{
								
							}
						}
					}
					break;



			}
			$singleStation = $singleStation->execute()->as_array();
					
			if(isset($singleStation[0]))
				$returnString = $returnString . $this->generateTableRow($color,$singleStation[0]['station_name'],$singleStation[0]['station_id'],$i);

		}

		return $returnString;
		
	}

	public function grabStationsRaw($line,$direction,$location,$nochange = false)
	{
		// $advisories = DB::select()
		// 		->from('line_info')
		// 		->where('filename', '=', $location) 	 	
		// 		->where()
		// 		->execute()->as_array();

		$direction = strtolower($direction); 

		if( $direction == "downtown"){
		$advisories_query = DB::query( Database::SELECT , "select * from line_info where filename = :filename and line_id = :line_id
			and ( bound_station_id > 1 or bound_station_id is NULL ); " ); }
		else {
			$advisories_query = DB::query( Database::SELECT , "select * from line_info where filename = :filename and line_id = :line_id
			and ( bound_station_id <= 1 or bound_station_id is NULL ); " ); }

		$advisories_query->param( ":filename" , $location); 
		$advisories_query->param( ":line_id" , $line); 

		$advisories = $advisories_query->execute()->as_array(); 


		// SERVICE_REPLACE_ID CODE:
		// No trains running : 2 (Done)
		// No trains between A & B : 3 
		// Trains run express from A to B : 0
		// Trains run local from A to B: 1
		// Trains skip {stations} : 4 (Cant do)

		$trainsNotRunning = array();

		foreach($advisories as $arr)
		{
			// print_r($arr);
			if($arr['service_replace_id'] == 2)
				array_push($trainsNotRunning, $arr['line_id']);
		}

		// print_r($trainsNotRunning);

		if(in_array($line, $trainsNotRunning))
		{
			return array();
		}

		$runsExpress = false;
		$runLocal = false;
		$notrainsbetween = false; 

		if(count($advisories) >0){
			$theReplaceID = $advisories[0]['service_replace_id'];
			if($theReplaceID == 0)
			{
				$runsExpress = true;
			}
			else if($theReplaceID == 1)
			{
				$runLocal = true;
			}
			else if($theReplaceID == 3)	// No Trains Between Case
			{
				// start_station_id = order_number, end_station_id = order_number
				// echo "Has No Train Between Case for ". $advisories[0]['start_station_id'] . ": " . $advisories[0]['end_station_id'] . "on $line"; 
				$notrainsbetween = true; 
				$notrainsbetweenpairarray = array($advisories[0]['start_station_id'] , $advisories[0]['end_station_id'] ); 
			}
		}


		

		// No Trains Between 3:

		// print_r($advisories); 
		$theLine = DB::select()
				->from('line_train')
				->where('line_id', '=', $line)
				->execute()->as_array();

		
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

// SERVICE_REPLACE_ID CODE:
// No trains running : 2
// No trains between A & B : 3
// Trains run express from A to B : 0
// Trains run local from A to B: 1
// Trains skip {stations} : 4

		
		$returnString ='';
		$numOfStation = sizeof($stations)-1;
		$returnArray = array();

		//echo $numOfStation."<br/>";

		if($nochange)
		{
			$runsExpress 	 = false;
			$runLocal 		 = false;
			$notrainsbetween = false; 
		}

		for($i=$numOfStation;$i>=0;$i--)
		{
			$singleStation = DB::select()
			->from('station')
			->where('station_id', '=', $stations[$i]['station_id']);
			
			$currentStationOrderID = $stations[$i]['order_number'];

			if( $notrainsbetween )
			{
				$start_station_order = $notrainsbetweenpairarray[0]; 
				$end_station_order = $notrainsbetween[1];
				if( ( ($start_station_order <= $currentStationOrderID) && ($currentStationOrderID <= $end_station_order) ) || 
				  	( ( $start_station_order >= $currentStationOrderID ) && ($currentStationOrderID >= $end_station_order ) ) 
				  )
				{
					$singleStation->where('station_id', '=', '-1'); 
				}
			}

			$color='black';
			switch($line)
			{	
				case 1:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else if($currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$runLocal = false;
							}
							
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else if($currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$runLocal = false;
							}
						}

					}
					else if($notrainsbetween)
					{

					}
					else{

					if($currentStationOrderID == 36)
						$singleStation = $singleStation->where('express', '=', 'true');
					}
					break;

				case 2:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}

						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}

						
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
							}
							else
							{
								
							}
						}

					}
					else {
						if($currentStationOrderID > 24 && $currentStationOrderID <41)
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 3:
					$color = 'red';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{


						if($currentStationOrderID > 6 && $currentStationOrderID < 23)
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
						break;
				
				case 4:
					$color = 'green';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else
					{
						if(($currentStationOrderID == 13) || ($currentStationOrderID > 14 && $currentStationOrderID < 34) || ($currentStationOrderID > 39 && $currentStationOrderID<= 54) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}

					break;

				case 5:
					$color = 'green';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
						if($currentStationOrderID > 15 &&  $currentStationOrderID < 34 )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 6:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'green';
					break;

				case 7:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'purple';
					break;

				case 8:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'green';
					break;

				case 9: 
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'purple';
					break;

				case 10:
					$color = 'blue';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
						if(($currentStationOrderID > 5 && $currentStationOrderID <27) || ($currentStationOrderID > 31 && $currentStationOrderID <44))
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 11:
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					$color = 'blue';
					break;

				case 12:
					$color = 'blue';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}else{
						if(($currentStationOrderID > 3 && $currentStationOrderID <20) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;					
				
				case 13:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 14:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else
					{
						if(($currentStationOrderID == 24) )
						{
							$singleStation = $singleStation->where('express', '=', 'false');
						}
						if(($currentStationOrderID > 25 && $currentStationOrderID <30) )
						{
							$singleStation = $singleStation->where('express', '=', 'true');
						}
					}
					break;

				case 15: 
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 16:
					$color = 'orange';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 17:
					$color = '#FCF141';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 18:
					$color = '#FCF141';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					else{
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

					}
					break;

				case 19:
					$color = '#FCF141';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}

					break;

				case 20:
					$color = 'lime';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 21:
					$color = '#a7a9ac';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 22:
					$color = 'brown';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 23:
					$color = 'brown';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					else if($runLocal)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation;
								
							}
							else
							{
								
							}
						}

					}
					break;

				case 24:
					$color = '#a7a9ac';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
						}
					}
					break;

				case 25:
					$color = '#a7a9ac';
					if($runsExpress)
					{
						if($direction == 'uptown')
						{
							if($currentStationOrderID < $advisories[0]['start_station_id'] && $currentStationOrderID > $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
							else
							{
								
							}
						}
						else
						{
							if($currentStationOrderID > $advisories[0]['start_station_id'] && $currentStationOrderID < $advisories[0]['end_station_id'])
							{
								$singleStation = $singleStation->where('express', '=', 'true');
								
							}
							else
							{
								
							}
						}
					}
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