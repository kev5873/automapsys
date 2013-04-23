<?php defined('SYSPATH') or die('No direct script access.');

class Controller_map extends Controller_Template {

	public $template = 'map';

    public function before()
    {
        // You can add actions to this array that will then use a different template
        if (in_array($this->request->action(), array('grab')))
        {
            $this->template = 'blank';
        }

        parent::before();
    }
	
	public function action_index()
	{
		$this->template->title   = 'MTA New York City Subway Service Advisories';
		$this->template->message = 'hello, world!';

		// This is feeder code, DO NOT DELETE YET!
		//$feeder = new Model_feed();
		//$feeder->processFeed(getcwd()."/a/s/status-1364696060.xml");

		$line = new Model_line();
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{
			$id = 12;
		}
		if(isset($_GET['direction']))
		{
			$direction = $_GET['direction'];
		}
		else
		{
			$direction = "downtown";
		}
		$this->template->line = $id;
		$this->template->direction = $direction;
		$this->template->routeDesignation = $line->getLineBullet($id);
		$this->template->routeDetail = $line->getLineDescription($id);	
	}

	public function action_grab()
	{
		$line = new Model_line();
		if(isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else
		{
			$id = 12;
		}
		if(isset($_POST['direction']))
		{
			$direction = $_POST['direction'];
		}
		else
		{
			$direction = "downtown";
		}

		echo json_encode($line->grabStationsRaw($id, $direction));
	}

}
