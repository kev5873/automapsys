<?php 
	defined('SYSPATH') OR die('No Direct Script Access');
	 
	Class Controller_Hello extends Controller
	{
		
	    public function action_index()
	    {
	        echo 'Hello World!';
	    }
	}
?>