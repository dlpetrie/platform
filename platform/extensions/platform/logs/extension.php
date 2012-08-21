<?php
/**
 * Part of the Logs Extension for Platform.
 *
 * @package    Logs
 * @version    1.0
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

return array(

	'info' => array(
		'name'        => 'Logs',
		'author'      => 'Cartalyst LLC',
		'description' => 'Creates and Manages logs.',
		'version'     => '1.0',
		'is_core'     => true,
	),

	'dependencies' => array(
		'menus',
		'users',
	),

	'bundles' => array(
		'handles'  => 'logs',
		'location' => 'path: '.__DIR__,
	),

	'events' => array(
		// don't set crud events for logs, as it will endlessly
		// loop in the crud model
	),

	'listeners' => function() {
		Logs\Libraries\Helpers::find_events();
	},

	'global_routes' => function() {

	},

	'rules' => array(

	),

);
