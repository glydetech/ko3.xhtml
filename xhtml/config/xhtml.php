<?php defined('SYSPATH') or die('No direct script access.');
	
return array(

	// Xhtml defaults
	'default' => array(
	
		// Doctype definition - should be self explanatory
		// One of the following constants:
		// 		Xhtml::DOCTYPE_HTML_4_01_STRICT
		// 		Xhtml::DOCTYPE_HTML_4_01_FRAMESET
		// 		Xhtml::DOCTYPE_HTML_4_01_TRANSITIONAL
		// 		Xhtml::DOCTYPE_XHTML_1_0_STRICT
		// 		Xhtml::DOCTYPE_XHTML_1_0_FRAMESET
		// 		Xhtml::DOCTYPE_XHTML_1_0_TRANSITIONAL
		// 		Xhtml::DOCTYPE_XHTML_1_1_STRICT
		'doctype'		=> Xhtml::DOCTYPE_XHTML_1_0_STRICT,
		
		// Language code - If not specified this is autodetected from i18n module
		//'langcode'	=> 'en-fr',
		
		// Additional attributes for the html tag
		//'htmlatts'	=> array(),
		
		// Default page body
		//'body'		=> 'default body',
		
		// Head defaults
		'head' => array(
		
			// Content of the title tag
			'title'		=> 'default title',
			
			// Meta tags
			'meta' => array(
				//'keywords'		=> 'keywords',
				//'description'	=> 'description of site',
			),
			
			// Link tags
			'links' => array(
				//'home' => array(
				//	'href'	=> '/',
				//	'rel'	=> 'home',
				//	'title'	=> 'Home Page',
				//),
			),
			
			// Styles
			'styles' => array(
				//'main styles' => 'styles.css',
			),
			
			// Script tags
			'scripts' => array(
				//'main scripts' => 'scripts.js',
			),
			
			// Inline scripts
			'codes' => array(
				//'default code' => 'var $i=0;',
			),
		),
	),
	
	// include meta tags detected from headers, can be null
	// array('content-type', 'expires')
	'meta_include_headers'	=> array('content-type'),

);

