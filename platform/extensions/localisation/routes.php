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


/*
 * --------------------------------------------------------------------------
 * Default routing for localisation.
 * --------------------------------------------------------------------------
 */
Route::any(ADMIN . '/localisation', 'localisation::admin.languages@index');


/**
 * Route /api/localisation/:country_code
 *
 *	<code>
 *		/api/localisation/gb => localisation::api.countries@index(gb)
 *	</code>
 */
# Don't think we need this anymore, since you are using different combinations 
#Route::any(API . '/localisation/([a-z]{2})', 'localisation::api.countries@index');


/**
 * Route /api/localisation/:local/datatable
 *
 *	<code>
 *		/api/localisation/countries/datatable => localisation::api.countries@datatable
 *	</code>
 */
Route::any(API . '/localisation/(:any)/datatable', 'localisation::api.(:1)@datatable');


/**
 * Route /api/localisation/:local/:country_code
 *
 *	<code>
 *		/api/localisation/country/gb => localisation::api.countries@index(gb)
 *	</code>
 */
Route::any(API . '/localisation/(:any)/(:any)', function( $local, $slug )
{
	return Controller::call('localisation::api.' . Str::plural( $local ) . '@index', array($slug));
});
