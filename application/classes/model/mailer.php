<?php defined('SYSPATH') or die('No direct script access.');

class Model_mailer extends Model
{

	public function insertEmail($email)
	{
		$result = DB::select('email')->from('reminder')->where('email' , '=' , $email)->execute()->as_array();
		if(!empty($result)) // does the record exists
		{
			return false; 
		}
		else
		{
			$query = DB::insert("reminder", array("email"))->values(array($email))->execute(); 
			return true;
		} 
	}
}
