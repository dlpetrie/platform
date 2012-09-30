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
 * @version    1.0.1
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

// All reserved API routes should be put here.
// Menu children cannot match these routes.
$reserved = array('active', 'active_path');

/**
 * Route flat menu children.
 */
Route::any(array(API.'/menus/((?!'.implode('|', $reserved).').*)/children/flat', API.'/menus/flat'), function($slug = false)
{
	return Controller::call('menus::api.menus@flat', array($slug));
});

/**
 * Route /api/menus/:children
 *
 *	<code>
 *		/api/menus/admin/children => menus::menus.api@children(admin)
 *	</code>
 */
Route::any(API.'/menus/((?!'.implode('|', $reserved).').*)/children', function($slug = null)
{
	return Controller::call('menus::api.menus@children', array($slug));
});

/**
 * Route /api/menus/:menu
 *
 *	<code>
 *		/api/menus/admin => menus::menus.api@index(admin)
 *	</code>
 */
Route::any(API.'/menus/((?!'.implode('|', $reserved).').*)', function($slug = null)
{
	return Controller::call('menus::api.menus@index', array($slug));
});

// Unset the $reserved variable
// from the global namespace
unset($reserved);

Route::controller(Controller::detect('menus'));

/* End of file routes.php */
/* Location: ./platform/extensions/platform/menus/routes.php */