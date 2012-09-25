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
		'name'        => 'Dashboard',
		'author'      => 'Cartalyst LLC',
		'description' => 'The main admin screen. The center of your website\'s adminstration.',
		'version'     => '1.1',
		'is_core'     => true
	),


    /*
     * -----------------------------------------
     * Extension dependencies.
     * -----------------------------------------
     */
	'dependencies' => array(
		'menus',
		'users',
		'settings'
	)
);

/* End of file extension.php */
/* Location: ./platform/extensions/platform/dashboard/extension.php */