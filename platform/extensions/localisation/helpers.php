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


/**
 * --------------------------------------------------------------------------
 * Function: country_statuses()
 * --------------------------------------------------------------------------
 *
 * Returns an array of the country statuses..
 *
 * @access   public
 * @return   array
 */
function country_statuses()
{
	return array(
		1 => Lang::line('general.enabled')->get(),
		0 => Lang::line('general.disabled')->get()
	);
}
