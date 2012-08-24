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
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Themes\Theme;

/**
 * Route /api/themes/:type/:name. The available types are stored
 * inside the Theme model.
 *
 *	<code>
 *		/api/themes/backend         => themes::themes.api.index(backend)
 *		/api/themes/backend/default => themes::themes.api.index(backend, default)
 *	</code>
 */
Route::any(API.'/themes/('.implode('|', Theme::types()).')/(:any?)', function($type, $name = null)
{
	return Controller::call('themes::api.themes@index', array($type, $name));
});

/**
 * Route /api/themes/:type/:name/options.
 *
 *	<code>
 *		/api/themes/backend/default/options => themes::themes.api.options(backend, default)
 *	</code>
 */
Route::any(API.'/themes/('.implode('|', Theme::types()).')/(:any)/options', function($type, $name)
{
	return Controller::call('themes::api.themes@options', array($type, $name));
});

Route::any(ADMIN.'/themes', function() {
	return Redirect::to(ADMIN.'/themes/frontend');
});

Route::controller(Controller::detect('themes'));
