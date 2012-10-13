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
use Platform\Localisation\Currency;


/**
 * --------------------------------------------------------------------------
 * Localisation > Currencies > API Class
 * --------------------------------------------------------------------------
 * 
 * Manage the currencies.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_API_Currencies_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns an array of all the currencies.
     *
     * If you want to retrieve information about a specific currency, you can
     * pass the currency code, the currency id or the currency slug as the 
     * last parameter.
     *
     *  <code>
     *      $all_currencies = API::get('localisation/currencies');
     *      $usd_currency   = API::get('localisation/currency/1');
     *      $usd_currency   = API::get('localisation/currency/usd');
     *      $usd_currency   = API::get('localisation/currency/us-dollar');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function get_index($currency_code)
    {
        // If we have the currency code, we return the information about that currency.
        //
        if ($currency_code != false)
        {
            // Get this currency information.
            //
            $currency = Currency::find($currency_code);

            // Check if the currency exists.
            //
            if (is_null($currency))
            {
                // Currency not found.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::currencies/message.error.not_found', array('currency' => $currency_code))->get()
                ), API::STATUS_NOT_FOUND);
            }

            // Return the currency information.
            //
            return new Response($currency);
        }

        // Get and return all the currencies.
        //
        return new Response(Currency::all());
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_index()
     * --------------------------------------------------------------------------
     *
     * Creates a new currency.
     *
     *  <code>
     *      API::post('localisation/currency');
     *  </code>
     *
     * @access   public
     * @return   Response
     */
    public function post_index()
    {
        // Create the currency.
        //
        $currency = new Currency();

        // Update the currency data.
        //
        $currency->name   = Input::get('name');
        $currency->slug   = \Str::slug(Input::get('name'));
        $currency->code   = strtoupper(Input::get('code'));
        $currency->locale = Input::get('locale');
        $currency->status = Input::get('status');

        try
        {
            // Save the currency.
            //
            if ($currency->save())
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::currencies/message.create.success', array('currency' => $currency->name))->get(),
                    'slug'    => $currency->slug
                ), API::STATUS_CREATED);
            }

            // An error occurred.
            //
            else
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::currencies/message.create.fail')->get(),
                    'errors'  => ($currency->validation()->errors->has()) ? $currency->validation()->errors->all() : array()
                ), ($currency->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => $e->getMessage()
            ), API::STATUS_BAD_REQUEST);
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_index()
     * --------------------------------------------------------------------------
     *
     * Edits a given currency using the provided currency id, currency code 
     * or by using the currency slug.
     *
     *  <code>
     *      $currency = API::put('localisation/currency/1');
     *      $currency = API::put('localisation/currency/usd');
     *      $currency = API::put('localisation/currency/us-dollar');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function put_index($currency_code)
    {
        // Get this currency information.
        //
        $currency = Currency::find($currency_code);

        // Now update the rules.
        //
        Currency::set_validation(array(
            'code' => 'required|size:3|unique:currencies,code,' . $currency->code . ',code'
        ));

        // Update the currency data.
        //
        $currency->name   = Input::get('name');
        $currency->slug   = \Str::slug(Input::get('name'));
        $currency->code   = strtoupper(Input::get('code'));
        $currency->status = ( ! $currency['default'] ? Input::get('status') : 1 );

        try
        {
            // Update the currency.
            //
            if ($currency->save())
            {
                // Return a response.
                //
                return new Response(array(
                    'slug'    => $currency->slug,
                    'message' => Lang::line('localisation::currencies/message.update.success', array('currency' => $currency['name']))->get()
                ));
            }
            else
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::currencies/message.update.fail', array('currency' => $currency['name']))->get(),
                    'errors'  => ($currency->validation()->errors->has()) ? $currency->validation()->errors->all() : array()
                ), ($currency->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => $e->getMessage()
            ), API::STATUS_BAD_REQUEST);
        }
    }



    /**
     * --------------------------------------------------------------------------
     * Function: delete_index()
     * --------------------------------------------------------------------------
     *
     * Deletes a given currency using the provided currency id, currency code 
     * or by using the currency slug.
     *
     *  <code>
     *      $currency = API::put('localisation/currency/1');
     *      $currency = API::put('localisation/currency/usd');
     *      $currency = API::put('localisation/currency/us-dollar');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function delete_index($currency_code)
    {
        try
        {
            // Get this currency information.
            //
            $currency = Currency::find($currency_code);
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::currencies/message.error.not_found', array('currency' => $currency_code))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Check if this is a default currency.
        //
        if ($currency['default'])
        {
            // Return a response.
            //
            return new Response( array(
                'message' => Lang::line('localisation::currencies/message.delete.single.being_used')->get()
            ), API::STATUS_BAD_REQUEST);
        }

        // Try to delete the currency.
        //
        try
        {
            // Delete the currency.
            //
            $currency->delete();

            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::currencies/message.delete.single.success', array('currency' => $currency->name))->get()
            ));
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response( array(
                'message' => Lang::line('localisation::currencies/message.delete.single.fail', array('currency' => $currency->name))->get()
            ), API::STATUS_BAD_REQUEST);
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_datatable()
     * --------------------------------------------------------------------------
     *
     * Returns fields required for a Platform.table.
     *
     *  <code>
     *      API::get('localisation/currencies/datatable');
     *  </code>
     *
     * @access   public
     * @param    string
     * @return   Response
     */
    public function get_datatable()
    {
        // Get the default currency.
        //
        $default_currency = strtoupper(Platform::get('localisation.site.currency'));


        $defaults = array(
            'select'   => array(
                'currencies.id'   => 'id',
                'currencies.name' => 'name',
                'currencies.code' => 'code',
                'currencies.slug' => 'slug'
            ),
            'where'    => array(),
            'order_by' => array('currencies.id' => 'asc')
        );

        // Count the total of currencies.
        //
        $count_total = Currency::count();

        // get the filtered count
        // we set to distinct because a user can be in multiple groups
        $count_filtered = Currency::count_distinct('currencies.id', function($query) use ($defaults)
        {
            // sets the where clause from passed settings
            return Table::count($query, $defaults);
        });

        // Set the pagination.
        //
        $paging = Table::prep_paging($count_filtered, 20);

        // Get the currencies.
        //
        $items = Currency::all(function($query) use ($defaults, $paging)
        {
            list($query, $columns) = Table::query($query, $defaults, $paging);

            return $query->select($columns);
        });

        // Return our data.
        //
        return new Response(array(
            'rows'             => ( $items ?: array() ),
            'count'            => $count_total,
            'count_filtered'   => $count_filtered,
            'paging'           => $paging,
            'default_currency' => $default_currency
        ));
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_default()
     * --------------------------------------------------------------------------
     *
     * Makes a currency the default currency on the system.
     *
     *  <code>
     *      $currency = API::put('localisation/currency/1');
     *      $currency = API::put('localisation/currency/usd');
     *      $currency = API::put('localisation/currency/us-dollar');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function put_default($currency_code)
    {
        // Get this currency information.
        //
        $currency = Currency::find($currency_code);
        
        // Check if the currency exists.
        //
        if (is_null($currency))
        {
            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::currencies/message.error.not_found', array('currency' => $currency_code))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Make the current default currency, not default anymore.
        //
        DB::table('currencies')->where('default', '=', 1)->update(array('default' => 0));

        // Make this currency the default.
        //
        DB::table('currencies')->where('code', '=', $currency['code'])->update(array('default' => 1));

        // Update the settings table.
        //
        DB::table('settings')
            ->where('extension', '=', 'localisation')
            ->where('type', '=', 'site')
            ->where('name', '=', 'currency')
            ->update(array('value' => $currency['code']));

        // Return a response.
        //
        return new Response(array(
            'message' => Lang::line('localisation::currencies/message.update.default', array('currency' => $currency->name))->get()
        ));
    }
}
