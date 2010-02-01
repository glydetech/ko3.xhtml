<?php defined('SYSPATH') or die('No direct script access.');

/**
* Xhtml singleton
* @package    Xhtml
* @author     Andrew Clarke
* @copyright  (c) 2010 Andrew Clarke
*/
class Xhtml {

	// Doctype constants

	const DOCTYPE_HTML_4_01_STRICT		 	= 'HTML 4.01 STRICT';
	const DOCTYPE_HTML_4_01_FRAMESET		= 'HTML 4.01 FRAMESET';
	const DOCTYPE_HTML_4_01_TRANSITIONAL	= 'HTML 4.01 TRANSITIONAL';
	const DOCTYPE_XHTML_1_0_STRICT			= 'XHTML 1.0 STRICT';
	const DOCTYPE_XHTML_1_0_FRAMESET		= 'XHTML 1.0 FRAMESET';
	const DOCTYPE_XHTML_1_0_TRANSITIONAL	= 'XHTML 1.0 TRANSITIONAL'; 
	const DOCTYPE_XHTML_1_1_STRICT			= 'XHTML 1.1 STRICT';
	
	// Public static properites
	
	/**
	* @var string HTML document type, one of xhtml::DOCTYPE_* constants
	*/
	public static $doctype;

	/**
	* @var string HTML language code, defaults to default kohana language
	*/
	public static $langcode;

	/**
	* @var array Attributes of the html tag as an array
	*/
	public static $htmlatts = array();

	/**
	* @var Head Instance of Head class
	*/
	public static $head;
	
	/**
	* @var View Body view
	*/
	public static $body;

	// Protected static properties

	/**
	* @var Xhtml Singleton instance
	*/
	protected static $_instance;
	
	// Singleton methods
	
	/**
	* Get the singleton instance of Xhtml.
	* @return  Xhtml
	*/
	public static function instance()
	{
		if (self::$_instance === NULL)
		{
			// Create a new instance
			self::$_instance = new self;
			
			// Set instance of Head class
			self::$head = Head::instance();
			
			// Set defaults from config
			$default = Kohana::config('xhtml.default');
			foreach (array('doctype', 'langcode', 'htmlatts', 'body') as $key)
			{
				if (isset($default[$key]) AND !empty($default[$key]))
					self::${$key} = $default[$key];
			}
			
			// overwrite langcode from i18n if not set
 			if (!isset(self::$langcode))
 				self::$langcode = i18n::$lang;
		}
		return self::$_instance;
	}

	/**
	* Enforce singleton behaviour
	* @return void
	*/
	final private function __construct()
	{
	}

	/**
	* Enforce singleton behaviour
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
			case 'htmlatts_extra':
			case 'htmlatts_all':
				$func = '_get_'.$key;
				return $this->{$func}();
		}
		return self::${$key};
	}

	/**
	* Magic method, sets static properties
	* @param   string  Property name
	* @param   mixed   value
	* @return  void
	*/
	public function __set($key, $value)
	{
		self::${$key} = $value;
	}

	/**
	* Magic method, determines if a static property is set and is not NULL.
	* @param   string  Property name
	* @return  boolean
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
	* @param   string  Property name
	* @return  void
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

	// Private htmlatts methods - generated properties

	private function _get_htmlatts_extra()
	{
		$attributes_extra = array();
		if (substr(self::$doctype, 0, 1) == 'X')
		{
			$attributes_extra['xmlns'] = 'http://www.w3.org/1999/xhtml';
			$attributes_extra['xml:lang'] = self::$langcode;
		} 
		$attributes_extra['lang'] = self::$langcode;
		return $attributes_extra;
	}

	private function _get_htmlatts_all()
	{
		return Arr::merge(self::$htmlatts, $this->_get_htmlatts_extra());
	}

} // End Xhtml
