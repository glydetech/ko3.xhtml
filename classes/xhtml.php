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
	public static function instance($file = NULL)
	{
		if (self::$_instance === NULL)
		{
			// Create a new instance
			self::$_instance = new self($file);

			// Set instance of Head class
			self::$head = Head::instance();

			// Set defaults from config
			$default = Kohana::config('xhtml.default');
			foreach (array('doctype', 'langcode', 'htmlatts', 'body') as $key)
			{
				if (isset($default[$key]) AND !empty($default[$key]))
					self::${$key} = $default[$key];
			}

		}
		return self::$_instance;
	}

	/**
	* Enforce singleton behaviour
	* @return void
	*/
	final private function __construct($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->body = View::factory($file);
		}
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
			case 'xhtml_doctype':
			case 'htmlatts_extra':
			case 'htmlatts_all':
			case 'is_xhtml':
			case 'lang':
			case 'charset':
			case 'contenttype':
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
	* Sets document type
	* @param string Document type - one of Xhtml::DOCTYPE_* constants
	* @return Head
	*/
	public function set_doctype($doctype)
	{
		$this->doctype = $doctype;
		return $this;
	}

	/**
	* Sets language code
	* @param string Language code
	* @return Head
	*/
	public function set_langcode($langcode)
	{
		$this->langcode = $langcode;
		return $this;
	}

	/**
	* Returns the rendered head tag
	* @param boolean echo result
	* @return string
	*/
	public function render($output = false)
	{
		// Set content-type header
		//Header('Content-Type: '.$this->contenttype.';; charset='.$this->charset);
		Request::current()->headers['Content-Type'] = $this->contenttype.'; charset='.$this->charset;
		$html = $this->xhtml_doctype;
		$html .= '<html'.Html::attributes($this->htmlatts_all).'>';
		$html .= Head::instance();
		$html .= '<body>'.$this->body.'</body>';
		$html .= '</html>';

		// Tidy
		if (extension_loaded('tidy') AND Kohana::config('xhtml.tidy_output'))
		{
			$tidyconfig = Kohana::config('xhtml.tidy_config');
			//$tidyconfig['output-xml'] = true;
			$charset = str_replace('-', '', $this->charset);
			$tidy = new tidy();
			$tidy->parseString($html, $tidyconfig, $charset);
			$tidy->cleanRepair();
			$html = (string)$tidy;
		}

		//Output
		if ($output)
			echo $html;
		return $html;
	}

	// Private methods - generated properties

	/**
	* Returns the doctype string
	* @return string
	*/
	private function _get_xhtml_doctype()
	{
		switch ($this->doctype)
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

	/**
	* Gets extra html attributes
	* @return array
	*/
	private function _get_htmlatts_extra()
	{
		$attributes_extra = array();
		if ($this->is_xhtml)
		{
			$attributes_extra['xmlns'] = 'http://www.w3.org/1999/xhtml';
			$attributes_extra['xml:lang'] = $this->lang;
		}
		$attributes_extra['lang'] = $this->lang;
		return $attributes_extra;
	}

	/**
	* Gets all html attributes
	* @return array
	*/
	private function _get_htmlatts_all()
	{
		return Arr::merge(self::$htmlatts, $this->_get_htmlatts_extra());
	}

	/**
	* Gets flag indicating xhtml
	* @return bool
	*/
	private function _get_is_xhtml()
	{
		return substr(self::$doctype, 0, 1) == 'X';
	}

	/**
	* Gets current language code - uses I18n if unset in Xhtml
	* @return string
	*/
	private function _get_lang()
	{
		if (isset($this->langcode))
		{
			return $this->langcode;
		}
		return I18n::$lang;
	}

	/**
	* Gets the current charset from Kohana
	* @return string
	*/
	private function  _get_charset()
	{
		return Kohana::$charset;
	}

	/**
	* Gets the content type based on what's accepted
	* @return string
	*/
	private function _get_contenttype()
	{
		if ($this->is_xhtml AND Request::accept_type('application/xhtml+xml') > 0)
		{
			return 'application/xhtml+xml';
		}
		return 'text/html';
	}

} // End Xhtml