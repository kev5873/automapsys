<?php defined('SYSPATH') or die('No direct script access.');

class Controller_ams extends Controller_Template {

	public $template = 'site';
	

	public function action_index()
	{
		$this->template->title   = 'MTA New York City Subway Service Advisories';
		$this->template->message = 'hello, world!';
		

		$station = DB::select()->from('station')->execute()->as_array();
	
		
		
		
		var_dump($station);
		/*
		$station				 = ORM::factory('station');
		$station->station_id 	 = 1;
		$station->station_name	 = 'TEST';
		$station->coordinatex	 = 500;
		$station->coordinatey	 = 300;
		$station->save();
		*/
	}

} // End Welcome
