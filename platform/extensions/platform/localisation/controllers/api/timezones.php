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
 * @version    1.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Platform\Localisation\Timezone;


/**
 * --------------------------------------------------------------------------
 * Localisation > Timezones > API Class
 * --------------------------------------------------------------------------
 * 
 * Manage the timezones.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_API__Timezones_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns an array of all the timezones.
     *
     *  <code>
     *      $all_timezones = API::get('localisation/timezones');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function get_index()
    {
        // Get and return all the countries.
        //
        return new Response(Timezone::all());
    }
}
