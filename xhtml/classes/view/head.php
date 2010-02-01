<?php

class View_Head extends View
{

	// Singleton instance
	//protected $_singleton;

	// Default data
	protected $_data = array(
	);

	// Array of singleton static property names
	//private $_static = array('title', 'meta', 'links', 'styles', 'scripts', 'codes');
	
	// Array of singleton generated property names
	//private $_generated = array('meta_extra', 'meta_all');
	
	// Array of extra view variables
	//protected $_view = array('meta_all');
	
	// Default filename
	//protected $_default_file = 'xhtml/head';
	
	// Factory method
	
	public static function factory($file = NULL, array $data = NULL)
	{
		return new View_Head($file, $data);
	}

	// Constructor method
	
	public function __construct($file = NULL, array $data = NULL)
	{
		// Get the singleton instance and bind to view
		//$this->_singleton = Head::instance();
		$this->set('head', Head::instance());//$this->_singleton);
		
		// Bind singleton static properties to view
		//foreach($this->_static as $key)
		//{
		//	$this->bind($key, Head::${$key});
		//}
		
		// Set the view filename
		if (!$file)
		{
			$file = 'xhtml/head';
		}
		$this->set_filename($file);
		
		// Add supplied data to the current data
		if ( $data !== NULL)
		{
			foreach ($data as $key => $value)
			{
				$this->head->$key = $value;
			}
		}
	}

// 	// Magic methods
// 	
// 	public function & __get($key)
// 	{
// 		// Pass through static properties
// 		//if (in_array($key, $this->_static))
// 		//{
// 		//	return Head::${$key};
// 		//}
// 		// Pass through generated properties
// 		//if (in_array($key, $this->_generated))
// 		//{
// 		//	// These have to be set because the result must be by ref
// 		//	$this->set($key, $this->_singleton->{$key});
// 		//}
// 		// Pass through standard properties
// 		return parent::__get($key);
// 	}
// 
// 	public function __set($key, $value)
// 	{
// 		//if (in_array($key, $this->_static))
// 		//{
// 		//	$this->_singleton->{$key} = $value;
// 		//	return;
// 		//}
// 		parent::__set($key, $value);
// 	}
// 
// 	public function __isset($key)
// 	{
// 		//if (in_array($key, $this->_static))
// 		//{
// 		//	return isset($this->_singleton->{$key});
// 		//}
// 		return parent::__isset($key);
// 	}
// 	
// 	public function __unset($key)
// 	{
// 		//if (in_array($key, $this->_static))
// 		//{
// 		//	unset($this->_singleton->{$key});
// 		//}
// 		parent::__unset($key);
// 	}
// 	
// 	// Render method
// 	
// 	public function render($file = NULL)
// 	{
// 		//foreach ($this->_view as $key)
// 		//{
// 			//$this->set($key, $this->head->{$key});
// 		//}
// 		return parent::render($file);
// 	}

} // End View_Head