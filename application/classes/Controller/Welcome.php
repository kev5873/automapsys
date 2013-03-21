<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$this->response->body('hello, balls!');
	}

	public function action_crash()
	{
		$this->response->body('crash');
	}

} // End Welcome
