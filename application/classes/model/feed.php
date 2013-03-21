<?php defined('SYSPATH') or die('No direct script access.');

class Model_feed extends Model
{

	public downloadFeed() {
		// Downloads the feed from the MTA and stores it in our system, saving the file
		// This will then be processed by the processFeed to produce an output
		// to be inserted into the database
	}

	public processFeed($file) {
		// Takes the downloaded data feed from the MTA and translates it to a
		// understandable format to insert it into the database
	}

	public insertData($data) {
		// Insert the processed feed into the database.
	}

}