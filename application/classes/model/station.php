<?php defined('SYSPATH') or die('No direct script access.');

class Model_Station extends ORM
{
//	protected $_has_one = array('station_id' => array());
//	protected $_primary_key = 'station_id';	

	protected $_primary_key = 'station_id';
	
	protected $_table_name = 'station'; 
	protected $_table_columns = array(
		'station_id' => 'primary_key', 
		'station_name' => null, 
		'coordinatex' => null, 
		'coordinatey' => null, 
	); 
}