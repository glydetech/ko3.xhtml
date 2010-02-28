<?php defined('SYSPATH') or die('No direct script access.');

/**
* Xhtml cache controller
* @package    Xhtml
* @author     Andrew Clarke
* @copyright  (c) 2010 Andrew Clarke
*/
class Controller_Xhtml extends Controller {

	public function action_cache($key, $ext = NULL)
	{
		$content = Kohana::cache($key);
		if (substr($ext, -4) == '.css')
		{
			$this->request->headers['Content-Type'] = 'text/css';
		}
		//$this->request->headers['Content-Type'] = 'application/javascript';
		$this->request->response = $content;
	}

}