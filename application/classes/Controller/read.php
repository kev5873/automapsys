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
		//$feeder->downloadFeed();
	// 'a/s/status-1366255687.xml'
<<<<<<< HEAD
	$fedArr = $feeder->processFeed($feeder->filestart);
=======

	$fedArr = $feeder->processFeed('a/s/status-1366515360.xml');// "a/s/status-1366515360.xml"
>>>>>>> 8700cba6f8e8334d6af2e44217aa8faff08e9ca4

	}

} // End Welcome
