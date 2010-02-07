<?php defined('SYSPATH') or die('No direct script access.');

/**
* Html Head singleton
* @package    Xhtml
* @author     Andrew Clarke
* @copyright  (c) 2010 Andrew Clarke
*/
class Head {

	// Public static properties

	/**
	* @var string Head title
	*/
	public static $title;

	/**
	* @var array Head meta tags
	*/
	public static $meta = array();

	/**
	* @var array Head link tags
	*/
	public static $links = array();
	
	/**
	* @var array Head style tags
	*/
	public static $styles = array();
	
	/**
	* @var array Head script tags
	*/
	public static $scripts = array();

	/**
	* @var array Head script blocks
	*/
	public static $codes = array();

	// Protected static properties

	/**
	* @var mixed Singleton static instance
	*/
	protected static $_instance;

	// Singleton methods

	/**
	* Get the singleton instance of Head.
	* @return Head
	*/
	public static function instance()
	{
		if (self::$_instance === NULL)
		{
			// Create a new instance
			self::$_instance = new self;
			
			// Set defaults from config
			$default = Kohana::config('xhtml.default.head');
			foreach ($default as $key => $value)
			{
			  self::${$key} = $value;
			}
		}
		return self::$_instance;
	}

	/**
	* Constructor method, enforce singleton behaviour
	* @return void
	*/
	final private function __construct()
	{
	}

	/**
	* Clone method, enforce singleton behaviour
	* @return void
	*/
	final private function __clone()
	{
	}

	// Magic methods

	/**
	* Magic method, gets overridden or static properties
	* @param string Property name
	* @return mixed Property value
	*/
	public function __get($key)
	{
		switch ($key)
		{
			case 'meta_extra':
			//case 'meta_all':
				$func = '_get_'.$key;
				return $this->{$func}();
		}	
		return self::${$key};
	}
	
	/**
	* Magic method, sets static properties
	* @param string Property name
	* @param mixed Property value
	* @return void
	*/
	public function __set($key, $value)
	{
		self::${$key} = $value;
	}

	/**
	* Magic method, determines if a static property is set and is not NULL.
	* @param string Property name
	* @return boolean
	*/
	public function __isset($key)
	{
		if (is_array(self::${$key}))
		{
			return ! empty(self::${$key}); 
		}
		return (isset(self::${$key}));
	}

	/**
	* Magic method, unsets a given static property
	* @param string Property name
	* @return void
	*/
	public function __unset($key)
	{
		if (is_array(self::${$key}))
		{
			self::${$key} = array();
		}
		self::${$key} = NULL;
		//unset(self::${$key});
	}
	
	/**
	* Magic method, returns the output of render(). If any exceptions are
	* thrown, the exception output will be returned instead.
	* @return  string
	*/
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana::exception_handler($e);
			return '';
		}
	}
	
	// Public methods

	/**
	* Returns the rendered head tag
	* @param boolean echo result
	* @return string
	*/
	public function render($output = false)
	{
		$close_single = (Xhtml::instance()->is_xhtml) ? ' />' : '>';
		$html = '<head>';
		$html .= '<title>'.$this->title.'</title>';
		foreach ($this->meta_extra as $key => $value)
			$html .= '<meta'.Html::attributes(array('http-equiv' => $key, 'content' => $value)).$close_single;
		foreach ($this->meta as $key => $value)
			$html .= '<meta'.Html::attributes(array('name' => $key, 'content' => $value)).$close_single;
		foreach ($this->links as $value)
			$html .= '<link'.Html::attributes($value).$close_single;
		foreach ($this->styles as $value)
			$html .= Html::style($value);
		foreach ($this->scripts as $value)
			$html .= Html::script($value);
		foreach ($this->codes as $value)
			$html .= '<script'.Html::attributes(array('type' => 'text/javascript')).'>//<![CDATA['."\n".$value."\n".'//]]></script>';
		$html .= '</head>';
		if ($output)
			echo $html;
		return $html;
	}

	// Private meta methods

	/**
	* Returns an array of extra meta entries
	* @return array
	*/
	private function _get_meta_extra()
	{
		// include detected headers specified
		$meta_extra = array();
		$includes = Kohana::config('xhtml.meta_include_headers');
		if ($includes)
		{
			foreach (Request::instance()->headers as $key => $value)
			{
				if (in_array(strtolower($key), $includes))
				{
					$meta_extra[$key] = trim($value);
				}
			}
		}
		return $meta_extra;
	}
	
	/**
	* Returns an array of all meta entries
	* @return array
	*/
// 	private function _get_meta_all()
// 	{
// 		return Arr::merge(self::$meta, $this->_get_meta_extra());
// 	}

// 	// Private script methods
// 
// 	/**
// 	 * Returns an array of extra script entries
// 	 * @return  array
// 	 */
// 	private function _get_scripts_extra()
// 	{
// 		// include detected headers specified
// 		$scripts_extra = array();
// 		foreach (self::$codes as $key => $value)
// 			$scripts_extra[$key] = 
// 		}
// 		return $scripts_extra;
// 	}
// 	
// 	/**
// 	* Returns an array of all script entries
// 	* @return  array
// 	*/
// 	private function _get_scripts_all()
// 	{
// 		return Arr::merge(self::$scripts, $this->_get_scripts_extra());
// 	}


// 	public function add_link($type, $message)
// 	{
// 		// Create a new message and timestamp it
// 		$this->_messages[] = array
// 		(
// 			'time' => date(self::$timestamp),
// 			'type' => $type,
// 			'body' => $message,
// 		);
// 
// 		return $this;
// 	}	

} // End Head
