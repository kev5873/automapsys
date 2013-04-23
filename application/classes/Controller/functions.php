<?php


	function getUpToDateStatus()	// returns the filename of the latest status file; 
	{
	$statuses = scandir(addslashes(getcwd().'\a\s')); 
	$recentfile = $statuses[0]; 
	foreach( $statuses as $status  )
	{
		if($status > $recentfile){
			$recentfile = $status;} 
	}
	// return $recentfile; 
	return 'a/s/'.$recentfile; 
	// return file_get_contents(addslashes(getcwd().'/a/s/'.$recentfile)); 
	}	
	
?>