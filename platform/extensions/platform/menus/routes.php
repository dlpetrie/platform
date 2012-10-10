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
 * All reserved API routes should be put here.
 * Menu children cannot match these routes.
 */
$reserved = implode('|', array(
    'active', 'active_path'
));


/**
 * Route /api/menus/flat
 *
 *  <code>
 *     /api/menus/flat => menus::menus.api@children
 *     /api/menus/admin/children/flat => menus::menus.api@children
 *  </code>
 */
Route::any(array(API . '/menus/((?!' . $reserved .').*)/children/flat', API . '/menus/flat'), 'menus::api.menus@flat');


/**
 * Route /api/menus/:children
 *
 *  <code>
 *     /api/menus/admin/children => menus::menus.api@children(admin)
 *  </code>
 */
Route::any(API . '/menus/((?!' . $reserved . ').*)/children', 'menus::api.menus@children');


/**
 * Route /api/menus/:menu
 *
 *  <code>
 *     /api/menus/admin => menus::menus.api@index(admin)
 *  </code>
 */
Route::any(API . '/menus/((?!' . $reserved . ').*)', 'menus::api.menus@index');


/*
 * Unset the $reserved variable from the global namespace.
 */
unset($reserved);
