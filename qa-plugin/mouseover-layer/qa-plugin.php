<?php

/*
	Question2Answer 1.4.3 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/mouseover-layer/qa-plugin.php
	Version: 1.4.3
	Date: 2011-09-27 18:06:46 GMT
	Description: Initiates mouseover layer plugin


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

/*
	Plugin Name: Mouseover Layer
	Plugin URI: 
	Plugin Description: Shows question content on mouse over in question lists
	Plugin Version: 1.0
	Plugin Date: 2011-03-17
	Plugin Author: Question2Answer
	Plugin Author URI: http://www.question2answer.org/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.4
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_layer('qa-mouseover-layer.php', 'Mouseover Layer');
	qa_register_plugin_module('module', 'qa-mouseover-admin-form.php', 'qa_mouseover_admin_form', 'Mouseover Layer');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/