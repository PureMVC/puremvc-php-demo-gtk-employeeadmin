#!/usr/bin/php
<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

function __autoload ($className)
{
	$paths = array
	(
		'org/puremvc/php/demos/phpgtk/employeeadmin/controller/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/model/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/model/enum/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/model/vo/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/view/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/view/component/',
		'org/puremvc/php/demos/phpgtk/employeeadmin/',
		'org/puremvc/php/core/',
		'org/puremvc/php/interfaces/',
		'org/puremvc/php/patterns/',
		'org/puremvc/php/patterns/command/',
		'org/puremvc/php/patterns/facade/',
		'org/puremvc/php/patterns/mediator/',
		'org/puremvc/php/patterns/observer/',
		'org/puremvc/php/patterns/proxy/',
		'helpers/'
	);
	
	foreach ($paths as $path)
	{
		if (file_exists ($file = $path . $className . '.php'))
		{
			require ($file);
			break;
		}
	}
}

include ('libs/PureMVC_PHP_1_0_2.php');
ini_set ('php-gtk.codepage', 'UTF-8');
$facade = AppFacade::getInstance ();
$facade->startup ();
