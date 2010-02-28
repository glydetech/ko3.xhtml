<?php defined('SYSPATH') or die('No direct script access.');

/**
* Xhtml cache controller
* @package    Xhtml
* @author     Andrew Clarke
* @copyright  (c) 2010 Andrew Clarke
*/
class Controller_Xhtml extends Controller {

	public function action_cache($keyandext)
	{
		if (substr($keyandext, -3) == 'css')
		{
			$key = substr($keyandext, 0, -3);
			$this->request->headers['Content-Type'] = 'text/css';
		}
		elseif (substr($keyandext, -2) == 'js')
		{
			$key = substr($keyandext, 0, -2);
		}
		else
		{
			return;
		}
		$content = Kohana::cache($key);
		//$this->request->headers['Content-Type'] = 'application/javascript';
		$this->request->response = $content;
	}

}