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

		// From 14-38 use express = true
		/* CONVERT THE FOR EACH LOOP BELOW TO A REGULAR FOR LOOP SO WE CAN FILTER BETWEEN 14-38 for 5 Train
		// can add a check to see if $line = 5
		foreach ($stations as $station)
		{
			$singleStation = DB::select()
				->from('station')
				->where('station_id', '=', $station['station_id'])
				//->where('express', '=', true)
				->execute()->as_array();
			//var_dump($singleStation);
				if(isset($singleStation[0]))
					echo $station['order_number'] . ' - ' .$singleStation[0]['station_name']  . '<br />';
		}
		*/
	}
}