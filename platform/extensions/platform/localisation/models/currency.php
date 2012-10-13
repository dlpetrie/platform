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

namespace Platform\Localisation;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Crud;


/**
 * --------------------------------------------------------------------------
 * Language model Class
 * --------------------------------------------------------------------------
 * 
 * Model to manage languages.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Currency extends Crud
{
    /**
     * Rules to be validated when creating or updating countries.
     *
     * @access   public
     * @param    array
     */
    public static $_rules = array(
        'name'          => 'required',
        'code'          => 'required|size:3|unique:currencies,code',
        'decimal_place' => 'required',
        'status'        => 'required'
    );


    /**
     * --------------------------------------------------------------------------
     * Function: set_validation()
     * --------------------------------------------------------------------------
     *
     * Updates or adds new rules to be validated.
     *
     * @access   public
     * @param    array
     * @return   boolean
     */
    public static function set_validation($rules)
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
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: find()
     * --------------------------------------------------------------------------
     *
     * A custom method to find currencies, we can use the currency id, currency code
     * or the currency slug to return currency information.
     *
     * @access   public
     * @param    mixed
     * @param    array
     * @param    array
     * @return   object
     */
    public static function find($condition = 'first', $columns = array('*'), $events = array('before', 'after'))
    {
        // Do we have the currency id ?
        //
        if (is_numeric($condition) and ! in_array($condition, array('first', 'last')))
        {
            // Execute the query.
            //
            return parent::find(function($query) use ($condition)
            {
                return $query->where('id', '=', $condition);
            }, $columns, $events);
        }

        // Do we have the currency code ?
        //
        elseif (strlen($condition) === 3)
        {
            // Execute the query.
            //
            return parent::find(function($query) use ($condition)
            {
                return $query->where('code', '=', strtoupper($condition));
            }, $columns, $events);
        }

        // We must have the currency slug.
        //
        elseif( ! in_array($condition, array('first', 'last')) )
        {
            // Execute the query.
            //
            return parent::find(function($query) use ($condition)
            {
                return $query->where('slug', '=', strtolower($condition));
            }, $columns, $events);
        }

        // Call parent.
        //
        return parent::find($condition, $columns, $events);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: update_currencies()
     * --------------------------------------------------------------------------
     *
     * Updates or adds new rules to be validated.
     *
     * @access   public
     * @param    boolean
     * @return   boolean
     */
    public static function update_currencies($force = false)
    {
        // First of all, check if we have the API Key for Openexchangerates.org set.
        //
        if ( ! $app_key = \Config::get('application.currency_appkey'))
        {
            return false;
        }

        // Check the log file to see when we ran the updater for the last time.
        //
        if (file_exists($file = \Bundle::path('localisation') . 'data' . DS . 'currencies.json') and $force === false)
        {
            // Check if we need to update currencies or not.
            //
            if (time() - filemtime($file) <= \Config::get('application.currency_auto_update'))
            {
                return false;
            }
        }

        // Check if we have cURL enabled.
        //
        if (function_exists('curl_version'))
        {
            // Make the API request.
            //
            $ch = curl_init('http://openexchangerates.org/api/latest.json?app_id=' . $app_key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            // Decode the response.
            //
            $json = json_decode($response);

            // Loop through the currencies, so we can update their rates.
            //
            foreach(static::all() as $currency)
            {
                $code = $currency['code'];
                $update = array(
                    'code' => $code,
                    'rate' => $json->rates->$code
                );

                // Update this currency.
                //
                \DB::table('currencies')->where('code', '=', $code)->update($update);
            }

            // Update the currencies file.
            //
            \File::put($file, $response);
        }

        // Currencies updated with success.
        //
        return true;
    }
}
