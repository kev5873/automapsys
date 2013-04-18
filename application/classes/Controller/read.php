<?php defined('SYSPATH') or die('No direct script access.');

include 'functions.php'; 

class Controller_read extends Controller_Template {

	/*
	  This file downloads the latest feed from the MTA.
	 */

	public $template = 'blank';

	public function action_index()
	{
		$this->template->title   = 'MTA New York City Subway Service Advisories';
		$this->template->message = 'hello, world!';

		$feeder = new Model_feed();
		//$feeder->downloadFeed();
	// 'a/s/status-1366255687.xml'
		$feeder->processFeed('a/s/status-1366311051.xml');

		echo "<br />";
		echo "<br />";
		echo "<br />";
		print_r($feeder->getStationStuff('asdf [2] asdf', 'E 180 St')); 
	}

} // End Welcome
