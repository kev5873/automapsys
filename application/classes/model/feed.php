<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public function downloadFeed() {
  	$url = 'http://www.mta.info/status/serviceStatus.txt';
  	$path = getcwd()."/a/s/status-".time().".xml";
    $newfname = $path;
    $file = fopen ($url, "rb");
    if ($file){
      $newf = fopen ($newfname, "wb");

      if ($newf)
      while(!feof($file))
      {
          fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
        }
    }

    if($file)
    {
      fclose($file);
    }

    if($newf)
    {
      fclose($newf);
    }
  }

	public function processFeed($file) {
    $subway = simplexml_load_file($file);
    for($i = 0; $i < 11; $i++)
    {
      echo "<hr />";
      echo $subway->subway[0]->line[$i]->name[0];
      echo $subway->subway[0]->line[$i]->status[0];
      echo $subway->subway[0]->line[$i]->text[0];
    }
		// Takes the downloaded data feed from the MTA and translates it to a
		// understandable format to insert it into the database
	}

	public function insertData($data) {
		// Insert the processed feed into the database.
	}

}