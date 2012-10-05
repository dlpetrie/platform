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

namespace Localisation;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Crud;


/**
 * --------------------------------------------------------------------------
 * Country model Class
 * --------------------------------------------------------------------------
 * 
 * Model to manage countries.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Country extends Crud
{
    /**
     * Rules to be validated when creating or updating countries.
     *
     * @access   public
     * @param    array
     */
    public static $_rules = array(
        'name'               => 'required',
        'iso_code_2'         => 'required|size:2|unique:countries,iso_code_2',
        'iso_code_3'         => 'required|size:3|unique:countries,iso_code_3',
        'iso_code_numeric_3' => 'required|numeric|unique:countries,iso_code_numeric_3',
        'status'             => 'required'
    );


    /**
     * --------------------------------------------------------------------------
     * Function: update_rules()
     * --------------------------------------------------------------------------
     *
     * Updates or adds new rules to be validated.
     *
     * @access   public
     * @param    array
     * @return   Response
     */
    public static function update_rules($rules)
    {
        // Make sure we have an array.
        //
        if ( ! is_array($rules))
        {
            return false;
        }

        // Loop through the rules.
        //
        foreach ($rules as $value => $rule)
        {
            // Set or update the rule.
            //
            static::$_rules[ $value ] = $rule;
        }

        // Done.
        //
        return false;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: find_custom()
     * --------------------------------------------------------------------------
     *
     * A custom method to find countries, we can use the country id, country iso
     * code 2 or the country slug to return country information.
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public static function find_custom($country_code)
    {
        return parent::find(function($query) use ($country_code)
        {
            // Do we have the country id ?
            //
            if(is_numeric($country_code))
            {
                return $query->where('id', '=', $country_code);
            }

            // Do we have the country iso code 2 ?
            //
            elseif (strlen($country_code) === 2)
            {
                return $query->where('iso_code_2', '=', strtoupper($country_code));
            }

            // We must have the country slug.
            //
            else
            {
                return $query->where('slug', '=', strtolower($country_code));
            }
        });
    }
}
