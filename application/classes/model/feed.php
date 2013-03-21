<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public function downloadFeed() {
		$url = 'http://www.mta.info/status/serviceStatus.txt';
		$path = getcwd()."/a/s/status-".time().".txt";
  $newfname = $path;
  $file = fopen ($url, "rb");
  if ($file) {
    $newf = fopen ($newfname, "wb");

    if ($newf)
    while(!feof($file)) {
      fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
    }
  }

  if ($file) {
    fclose($file);
  }

  if ($newf) {
    fclose($newf);
  }
	}

	public function processFeed($file) {
		// Takes the downloaded data feed from the MTA and translates it to a
		// understandable format to insert it into the database
	}

	public function insertData($data) {
		// Insert the processed feed into the database.
	}

}