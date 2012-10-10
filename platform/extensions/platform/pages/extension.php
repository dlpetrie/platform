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
 * Return the extension data.
 * --------------------------------------------------------------------------
 */
return array(
	/*
     * -----------------------------------------
	 * Extension information.
     * -----------------------------------------
	 */
	'info' => array(
		'name'        => 'Pages',
		'author'      => 'Cartalyst LLC',
		'description' => 'An extension to manage pages until a CMS is completed.',
		'version'     => '1.0',
		'is_core'     => true,
	),


    /*
     * -----------------------------------------
     * Extension routes.
     * -----------------------------------------
     */
	'routes' => function(){
		Route::any('/', 'pages::pages@index');

		Route::any('(:any)(/.*)?', function($page = 'index', $params = null) {

			// check if the page is a bundle
			if ( ! Bundle::exists($page))
			{
				$page = ($page == 'home') ? 'index' : $page;
				$params = explode('/', substr($params, 1));

				return Controller::call('pages::pages@'.$page, $params);
			}
		});
	}
);
