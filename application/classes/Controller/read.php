<?php defined('SYSPATH') or die('No direct script access.');

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
		$feeder->downloadFeed();
	// 'a/s/status-1366255687.xml'
	$fedArr = $feeder->processFeed('a/s/status-1367297276.xml');

	}

} // End Welcome
