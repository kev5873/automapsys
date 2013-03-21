<?php defined('SYSPATH') or die('No direct script access.');

class Controller_read extends Controller_Template {

	public $template = 'site';

	public function action_index()
	{
		$this->template->title   = 'MTA New York City Subway Service Advisories';
		$this->template->message = 'hello, world!';
	}

} // End Welcome
