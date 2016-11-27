<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

if(ENVIRONMENT !== 'production'){
	$hook['display_override'][] = array(
		'class'  	=> 'Develbar',
	    'function' 	=> 'debug',
	    'filename' 	=> 'Develbar.php',
	    'filepath' 	=> 'third_party/DevelBar/hooks'
	);
}else{
	$hook['display_override'][] = array(
	    'class' => 'Minifyhtml',
	    'function' => 'output',
	    'filename' => 'Minifyhtml.php',
	    'filepath' => 'hooks',
	    'params' => array()
	);
}