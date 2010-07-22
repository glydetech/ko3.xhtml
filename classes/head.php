<?php defined('SYSPATH') or die('No direct script access.');

/**
* Html Head singleton
* @package    Xhtml
* @author     Andrew Clarke
* @copyright  (c) 2010 Andrew Clarke
* @modified	2010-07-14 23:21
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
	public static $metas = array();

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
	* @param array Default values
	* @return Head
	*/
	public static function instance(array $data = array())
	{
		// Check if instance exists
		if (self::$_instance === NULL)
		{

			// Create a new instance
			self::$_instance = new self;

			// Add values from config file
			self::$_instance->add(Kohana::config('xhtml.default.head'));
		}

		// Add values from supplied data
		self::$_instance->add($data);

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
			case 'merged_scripts':
			case 'cached_scripts':
			case 'merged_styles':
			case 'cached_styles':
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

	/**
	* Magic method, provides the add_*() methods
	* @param mixed Method name
	* @param mixed Arguments
	* @return Head
	*/
	public function __call($name, $args = NULL)
	{
		if (substr($name, 0, 4) == 'add_')
		{
			$key = substr($name, 4);
			if (is_array($this->{$key.'s'}))
			{
				self::${$key.'s'}[$args[0]] = $args[1];
			}
		}
		return $this;
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
		foreach ($this->metas as $key => $value)
			$html .= '<meta'.Html::attributes(array('name' => $key, 'content' => $value)).$close_single;
		foreach ($this->links as $value)
			$html .= '<link'.Html::attributes($value).$close_single;
			
		// styles
		if (Kohana::config('xhtml.cache_styles'))
		{
			$html .= Html::style($this->cached_styles);
		}
		else
		{		
			foreach ($this->styles as $value)
			{
				$style_file = NULL;
				$style_atts = array();
				$style_cond = NULL;
				if (is_string($value))
				{
					$style_file = $value;
				}
				elseif (is_array($value))
				{
					if (isset($value['file']))
					{
						$style_file = $value['file'];
						unset($value['file']);
					}
					if (isset($value['condition']))
					{
						$style_cond = $value['condition'];
						unset($value['condition']);
					}
					$style_atts = $value;
				}
				if ($style_file)
				{
					if ($style_cond)
						$html .= '<!--[if '.$style_cond.']>';
					$html .= Html::style($style_file, $style_atts);
					if ($style_cond)
						$html .= '<![endif]-->';
				}
			}
		}
		
		// scripts
		if (Kohana::config('xhtml.cache_scripts'))
		{
			$html .= Html::script($this->cached_scripts);
		}
		else
		{
			foreach ($this->scripts as $file)
				$html .= Html::script($file);
			foreach ($this->codes as $code)
				$html .= '<script'.Html::attributes(array('type' => 'text/javascript')).'>//<![CDATA['."\n".$code."\n".'//]]></script>';
		}
		
		$html .= '</head>';
		if ($output)
			echo $html;
		return $html;
	}

	/**
	* Adds data, handling a single value or array of values, merging arrays
	* @param mixed Value name or an array of values
	* @param mixed Value data
	* @return Head
	*/
	public function add($key, $value = NULL)
	{
		// Process an array of keys
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->add($k, $v);
			}
			return $this;
		}

		// Process a single key and array value
		if (is_array($value) AND is_array($this->{$key}))
		{
			$this->{$key} = Arr::merge($this->{$key}, $value);
			return $this;
		}
 
		// process a single key
		$this->{$key} = $value;
		return $this;
	}
	
	/**
	* Sets the head title
	* @param string The title string
	* @return Head
	*/
	public function set_title($value)
	{
		return $this->add('title', $value);
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

	private function _get_cached_scripts()
	{
		$data = $this->merged_scripts;
 		$key = sha1($data);
 		$cache = Kohana::cache($key);
 		if ($cache == NULL)
 		{
 			Kohana::cache($key, $data, Kohana::config('xhtml.cache_lifetime'));
 			$cache = Kohana::cache($key);
 		}
		return 'xhtml/cache/'.$key.'js';
	}

	/**
	* Merges scripts
	*
	*/
	private function _get_merged_scripts()
	{
		$html = '';
		foreach ($this->scripts as $file)
		{
			$html .= file_get_contents($file);//Html::script($file);
		}
		foreach ($this->codes as $code)
		{
			//$html .= '<script'.Html::attributes(array('type' => 'text/javascript')).'>//<![CDATA['."\n".$value."\n".'//]]></script>';
			$html .= $code;
		}
		return $html;
	}

	private function _get_cached_styles()
	{
		$data = $this->merged_styles;
 		$key = sha1($data);
 		$cache = Kohana::cache($key);
 		if ($cache == NULL)
 		{
 			Kohana::cache($key, $data, Kohana::config('xhtml.cache_lifetime'));
 			$cache = Kohana::cache($key);
 		}
		return 'xhtml/cache/'.$key.'css';
	}

	/**
	* Merges styles
	*
	*/
	private function _get_merged_styles()
	{
		$html = '';
		foreach ($this->styles as $file)
		{
			$html .= file_get_contents($file);//Html::script($file);
		}
		return $html;
	}	
} // End Head