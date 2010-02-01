<?php

class View_Xhtml extends View
{

	// Singleton instance
	//protected $_singleton;

	// Default data
	protected $_data = array(
	//	'doctype' => Xhtml::DOCTYPE_XHTML_1_0_FRAMESET,
	);

	// Array of singleton static property names
	//protected $_static = array('doctype', 'langcode', 'htmlatts', 'body', 'head');
	
	// Array of singleton generated property names
	//protected $_generated = array('htmlatts_extra', 'htmlatts_all');
	
	// Default filenema
	//protected $_default_file = 'xhtml/template';
	
	// Factory method
	
	public static function factory($file = NULL, array $data = NULL)
	{
		return new View_Xhtml($file, $data);
	}

	// Constructor method
	
	public function __construct($file = NULL, array $data = NULL)
	{
		$this->set('xhtml', Xhtml::instance());
		$this->set('head', View_Head::factory());
		$this->bind('body', Xhtml::$body);
		// Get the singleton instance
		//$this->_singleton = Xhtml::instance();
		//$this->_singleton->head = new View_Head();
		// Bind singleton static properties to view
		//foreach($this->_static as $key)
		//{
		//	$this->bind($key, Xhtml::${$key});
		//}

		
		//$this->_head = new View_Head();
		//$this->set('head', new View_Head());

		//$this->_head = View_Head::factory();
		//$this->bind('head', $this->_head);
		//$this->set('head', View_Head::factory());

		// Set the view filename
		if (!$file)
		{
			$file = 'xhtml/template';
		}
		$this->set_filename($file);
		
		// Add supplied data to the current data
		if ( $data !== NULL)
		{
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}
	
	// Magic methods
	
	public function & __get($key)
	{
// 		// Pass through static properties
// 		if (in_array($key, $this->_static))
// 		{
// 			return Xhtml::${$key};
// 		}
// 		// Pass through generated properties
// 		if (in_array($key, $this->_generated))
// 		{
// 			$this->set($key, $this->_singleton->{$key});
// 		}
		// Locally generated properties
		switch ($key)
		{
			case 'xhtml_doctype':
				$func = '_get_'.$key;
				$this->set($key, $this->{$func}());
		}
		// Pass through standard properties
		return parent::__get($key);
	}

// 	public function __set($key, $value)
// 	{
// 		if (in_array($key, $this->_static))
// 		{
// 			$this->_singleton->{$key} = $value;
// 			return;
// 		}
// 		parent::__set($key, $value);
// 	}

// 	public function __isset($key)
// 	{
// 		if (in_array($key, $this->_static))
// 		{
// 			return isset($this->_singleton->{$key});
// 		}
// 		return parent::__isset($key);
// 	}
	
// 	public function __unset($key)
// 	{
// 		if (in_array($key, $this->_static))
// 		{
// 			unset($this->_singleton->{$key});
// 		}
// 		parent::__unset($key);
// 	}

 	public function render($file = NULL)
 	{
		$this->set('xhtml_doctype', $this->xhtml_doctype);
		//$this->set('htmlatts_all', $this->htmlatts_all);
 		//$this->_singleton->head = new View_Head();
 		return parent::render($file);
 	}

	// Private xhtml methods - generated properties

	/**
	* Returns the doctype string
	* @return string
	*/
	private function _get_xhtml_doctype()
	{
		switch ($this->xhtml->doctype)
		{
			case xhtml::DOCTYPE_HTML_4_01_STRICT:
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
			case xhtml::DOCTYPE_HTML_4_01_FRAMESET:
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
			case xhtml::DOCTYPE_HTML_4_01_TRANSITIONAL:
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
			case xhtml::DOCTYPE_XHTML_1_0_STRICT:
				return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
			case xhtml::DOCTYPE_XHTML_1_0_FRAMESET:
				return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
			case xhtml::DOCTYPE_XHTML_1_0_TRANSITIONAL:
				return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			case xhtml::DOCTYPE_XHTML_1_1_STRICT:
				return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
		}
	}

} // End View_Xhtml