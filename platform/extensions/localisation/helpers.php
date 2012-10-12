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


/**
 * --------------------------------------------------------------------------
 * Function: general_statuses()
 * --------------------------------------------------------------------------
 *
 * Returns an array of the general statuses.
 *
 * @access   public
 * @return   array
 */
function general_statuses()
{
    return array(
        1 => Lang::line('general.enabled')->get(),
        0 => Lang::line('general.disabled')->get()
    );
}


/**
 * --------------------------------------------------------------------------
 * Function: countries()
 * --------------------------------------------------------------------------
 *
 * Returns an array of countries, that we can use on form select menus.
 *
 * @access   public
 * @return   array
 */
function countries()
{
    // Initiate an empty array.
    //
    $countries = array();

    // Grab the countries from the database.
    //
    foreach (Localisation\Country::all() as $country)
    {
        $countries[ $country['id'] ] = $country['name'];
    }

    // Return the countries.
    //
    return $countries;
}


/**
 * --------------------------------------------------------------------------
 * Function: languages()
 * --------------------------------------------------------------------------
 *
 * Returns an array of languages, that we can use on form select menus.
 *
 * @access   public
 * @return   array
 */
function languages()
{
    // Initiate an empty array.
    //
    $languages = array();

    // Grab the languages from the database.
    //
    foreach (Localisation\Language::all() as $language)
    {
        $languages[ $language['id'] ] = $language['name'];
    }

    // Return the languages.
    //
    return $languages;
}


/**
 * --------------------------------------------------------------------------
 * Function: currencies()
 * --------------------------------------------------------------------------
 *
 * Returns an array of currencies, that we can use on form select menus.
 *
 * @access   public
 * @return   array
 */
function currencies()
{
    // Initiate an empty array.
    //
    $currencies = array();

    // Grab the currencies from the database.
    //
    foreach (Localisation\Currency::all() as $currency)
    {
        $currencies[ $currency['id'] ] = $currency['name'];
    }

    // Return the currencies.
    //
    return $currencies;
}


/**
 * --------------------------------------------------------------------------
 * Function: timezones()
 * --------------------------------------------------------------------------
 *
 * Returns an array of timezones, that we can use on form select menus.
 *
 * @access   public
 * @return   array
 */
function timezones()
{
    // Initiate an empty array.
    //
    $timezones = array();

    // Grab the timezones.
    //
    foreach (Localisation\Timezone::all() as $item => $value)
    {
        $timezones[ $item ] = $value;
    }

    // Return the timezones.
    //
    return $timezones;
}
