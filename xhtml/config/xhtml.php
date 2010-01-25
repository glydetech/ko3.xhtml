<?php defined('SYSPATH') or die('No direct script access.');
	
return array(

	// Application defaults
	'default' => array(
		'doctype'		=> xhtml::DOCTYPE_XHTML_1_0_STRICT,
		//'langcode'	=> 'en-fr',
		//'htmlatts'	=> array(),
		'head' => array(
			'title'		=> 'default title',
			'meta' => array(
				'keywords'		=> 'keywords',
				'description'	=> 'description of site',
			),
			'links' => array(
				'home' => array(
					'href'	=> '/',
					'rel'	=> 'home',
					'title'	=> 'Home Page',
				),
			),
			'styles' => array(
				'main styles' => 'styles.css',
			),
			'scripts' => array(
				'main scripts' => 'scripts.js',
			),
			'codes' => array(
				'default code' => 'var $i=0;',
			),
		),
	),
	
	// include meta tags detected from headers, can be null
	// array('content-type', 'expires')
	'meta_include_headers'	=> array('content-type'),

);

