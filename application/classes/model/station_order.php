<?php defined('SYSPATH') or die('No direct script access.');

class Model_station_order extends ORM
{
	protected $_belongs_to = array('station_id' => array());
	protected $_primary_key = 'line_id';
}