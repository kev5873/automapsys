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

		$returnString = '';

		foreach ($stations as $station)
		{
			$singleStation = DB::select()
				->from('station')
				->where('station_id', '=', $station['station_id'])
				//->where('express', '=', true)
				->execute()->as_array();

			if(isset($singleStation[0]))
				$returnString = $returnString . '<tr> <td style="background-color: #ff3333; padding: 15px 0px 15px 0px;"><img src="'.URL::base().'a/i/stationstop16px.png" /></td> <td style="padding-left: 15px;">'.$singleStation[0]['station_name'].'</td> </tr>';
		}
		return $returnString;
		
	}
}