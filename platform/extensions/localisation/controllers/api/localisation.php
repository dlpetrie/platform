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
 * Localisation > API Class
 * --------------------------------------------------------------------------
 * 
 * This is basically an alias to get the countries, or a country information.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_API_Localisation_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns an array of all the countries.
     *
     * Providing a slug as the second parameter will return that country itself.
	 *
     *  <code>
     *      $all        = API::get('localisation');
     *      $gb_country = API::get('localisation/gb');
     *  </code>
     *
     * @access   public
     * @param    string
     * @return   Response
     */
	public function get_index($country_code)
	{
		// If we have a country code, we return the information about that country.
		//
		if (strlen($country_code) === 2)
		{
			return new Response(API::get('localisation/countries/' . $country_code));
		}

		// Return all the countries.
		//
		return new Response(API::get('localisation/countries'));
	}
}
