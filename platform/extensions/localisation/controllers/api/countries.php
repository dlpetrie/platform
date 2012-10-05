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
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Localisation\Country;


/**
 * --------------------------------------------------------------------------
 * Localisation > Countries > API Class
 * --------------------------------------------------------------------------
 * 
 * Manage the countries.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_API_Countries_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns an array of all the countries.
     *
     * If you want to retrieve information about a specific country, you can
     * pass the country iso code 2 or the country slug as the last parameter.
     *
     *  <code>
     *      $all_countries = API::get('localisation');
     *      $all_countries = API::get('localisation/countries');
     *      $gb_country    = API::get('localisation/232');
     *      $gb_country    = API::get('localisation/gb');
     *      $gb_country    = API::get('localisation/united-kingdom');
     *      $gb_country    = API::get('localisation/country/232');
     *      $gb_country    = API::get('localisation/country/gb');
     *      $gb_country    = API::get('localisation/country/united-kingdom');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function get_index($country_code)
    {
        // If we have a country code, we return the information about that country.
        //
        if ($country_code != false)
        {
            // Get this country information.
            //
            $country = Country::find_custom($country_code);

            // Check if the country exists.
            //
            if (is_null($country))
            {
                // Country not found.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::countries/message.error.not_found', array('country' => $country_code))->get()
                ), API::STATUS_NOT_FOUND);
            }

            // Get the list of regions of this country.
            //
            #$regions = API::get('localisation/regions/' . $country_code);
            $regions = array();

            // Add the regions to the country array.
            //
            $country['regions'] = $regions;

            // Return the country information.
            //
            return new Response($country);
        }

        // Get and return all the countries.
        //
        return new Response(Country::all());
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_index()
     * --------------------------------------------------------------------------
     *
     * Creates a new country.
     *
     *  <code>
     *      API::post('localisation/country');
     *  </code>
     *
     * @access   public
     * @return   Response
     */
    public function post_index()
    {
        // Create the country.
        //
        $country = new Country(Input::get());

        try
        {
            // Save the country.
            //
            if ($country->save())
            {
                return new Response(array(
                    'message'      => Lang::line('localisation::countries/message.create.success')->get(),
                    'country_slug' => $country->slug
                ), API::STATUS_CREATED);
            }

            // An error occurred.
            //
            else
            {
                return new Response(array(
                    'message' => Lang::line('users::messages.users.create.error')->get(),
                    'errors'  => ($user->validation()->errors->has()) ? $user->validation()->errors->all() : array()
                ), ($user->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
            // 
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
     * Edits a country using the provided country id, country iso code 2 or the
     * provided country slug.
     *
     *  <code>
     *      API::put('localisation/country/232');
     *      API::put('localisation/country/gb');
     *      API::put('localisation/country/united-kingdom');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function put_index($country_code)
    {
        // Get this country information.
        //
        $country = Country::find_custom($country_code);

        // Now update the rules.
        //
        Country::update_rules(array(
            'iso_code_2'         => 'required|size:2|unique:countries,iso_code_2,' . $country->iso_code_2 . ',iso_code_2',
            'iso_code_3'         => 'required|size:3|unique:countries,iso_code_3,' . $country->iso_code_3 . ',iso_code_3',
            'iso_code_numeric_3' => 'required|numeric|unique:countries,iso_code_numeric_3,' . $country->iso_code_numeric_3 . ',iso_code_numeric_3'
        ));

        // Update the country data.
        //
        $country->name               = Input::get('name');
        $country->slug               = \Str::slug(Input::get('name'));
        $country->iso_code_2         = Input::get('iso_code_2');
        $country->iso_code_3         = Input::get('iso_code_3');
        $country->iso_code_numeric_3 = Input::get('iso_code_numeric_3');
        $country->region             = ( Input::get('region') ?: $country['region'] );
        $country->subregion          = ( Input::get('subregion') ?: $country['subregion'] );
        $country->currency           = ( Input::get('currency') ?: $country['currency'] );
        $country->status             = Input::get('status');

        try
        {
            // Update the country.
            //
            if ($country->save())
            {
                return new Response(array(
                    'slug'    => $country->slug,
                    'message' => Lang::line('localisation::countries/message.update.success', array('country' => $country['name']))->get()
                ));
            }
            else
            {
                return new Response(array(
                    'message' => Lang::line('localisation::countries/message.update.fail', array('country' => $country['name']))->get(),
                    'errors'  => ($country->validation()->errors->has()) ? $country->validation()->errors->all() : array(),
                ), ($country->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
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
     * Delete a given country using the provided country id, country iso code 2 
     * or using the country slug.
     *
     *  <code>
     *      $country = API::delete('localisation/country/232');
     *      $country = API::delete('localisation/country/gb');
     *      $country = API::delete('localisation/country/united-kingdom');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    # TODO :
    #
    #   * Check if the country is the system default country !
    #
    public function delete_index($country_code)
    {
        try
        {
            // Get this country information.
            //
            $country = Country::find_custom($country_code);
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::countries/message.error.not_found', array('country' => $country_code))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Try to delete the country.
        //
        try
        {
            // Delete the country.
            //
            $country->delete();

            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::countries/message.delete.single.success', array('country' => $country->name))->get()
            ));
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response( array(
                'message' => Lang::line('localisation::countries/message.delete.single.fail', array('country' => $country->name))->get()
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
     *      API::get('localisation/countries/datatable');
     *  </code>
     *
     * @access   public
     * @param    string
     * @return   Response
     */
    public function get_datatable()
    {
        $defaults = array(
            'select'   => array(
                'countries.id'         => 'ID',
                'countries.name'       => 'Name',
                'countries.iso_code_2' => 'ISO Code 2',
                'countries.slug'       => 'slug'
            ),
            'where'    => array(),
            'order_by' => array('countries.name' => 'asc')
        );

        // Count the total of countries.
        //
        $count_total = Country::count();

        // get the filtered count
        // we set to distinct because a user can be in multiple groups
        $count_filtered = Country::count_distinct('countries.id', function($query) use ($defaults)
        {
            // sets the where clause from passed settings
            return Table::count($query, $defaults);
        });

        // set paging
        $paging = Table::prep_paging($count_filtered, 20);

        $items = Country::all(function($query) use ($defaults, $paging)
        {
            list($query, $columns) = Table::query($query, $defaults, $paging);

            return $query->select($columns);
        });


        return new Response(array(
            'rows'           => ( $items ?: array() ),
            'count'          => $count_total,
            'count_filtered' => $count_filtered,
            'paging'         => $paging,
        ));
    }
}
