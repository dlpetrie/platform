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
 *
 */
$reserved = implode('|', Platform\Themes\Theme::types());


/**
 * Route /api/themes/:type/:name.
 *
 * The available types are stored inside the Theme model.
 *
 *  <code>
 *      /api/themes/backend         => themes::themes.api.index(backend)
 *      /api/themes/backend/default => themes::themes.api.index(backend, default)
 *  </code>
 */
Route::any(API . '/themes/(' . $reserved . ')/(:any?)', 'themes::api.themes@index');


/**
 * Route /api/themes/:type/:name/options.
 *
 *  <code>
 *      /api/themes/backend/default/options => themes::themes.api.options(backend, default)
 *  </code>
 */
Route::any(API . '/themes/(' . $reserved . ')/(:any)/options', 'themes::api.themes@options');


/**
 * Route /admin/themes/
 *
 * This shows the frontend themes listing page.
 *
 */
Route::any(ADMIN . '/themes', 'themes::admin.themes@frontend');


/**
 * Route /admin/themes/backend/edit/default
 *
 * Another route to edit themes.
 *
 */
Route::any(ADMIN . '/themes/(' . $reserved . ')/edit/(:any)', 'themes::admin.themes@edit');


/*
 * Unset the $reserved variable from the global namespace.
 *
 */
unset($reserved);
