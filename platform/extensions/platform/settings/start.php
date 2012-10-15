<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * Register some namespaces.
 * --------------------------------------------------------------------------
 */
Autoloader::namespaces(array(
    'Platform\\Settings\\Widgets' => __DIR__ . DS . 'widgets',
    'Platform\\Settings\\Model'   => __DIR__ . DS . 'models'
));

// Filesystem Errors
//
Event::listen(Config::get('filesystem::filesystem.event.fallback'), function($message) {

	// Front or Back end controller?
	//
	if (URI::segment(1) == ADMIN)
	{
		$message_type = Platform::get('settings.filesystem.backend_fallback_message');
	}
	else
	{
		$message_type = Platform::get('settings.filesystem.fronted_fallback_message');
	}

	if (in_array($message_type, array('error', 'info', 'success', 'warning')))
	{
		Platform::messages()->{$message_type}($message);
	}
});

Event::listen(Config::get('filesystem::filesystem.event.failed'), function($message) {

	// Front or Back end controller?
	//
	if (URI::segment(1) == ADMIN)
	{
		$message_type = Platform::get('settings.filesystem.backend_failed_message');
	}
	else
	{
		$message_type = Platform::get('settings.filesystem.frontend_failed_message');
	}

	if (in_array($message_type, array('error', 'info', 'success', 'warning')))
	{
		Platform::messages()->{$message_type}($message);
	}
});