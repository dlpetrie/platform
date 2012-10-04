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
     *      $all        = API::get('localisation');
     *      $all        = API::get('localisation/countries');
     *      $gb_country = API::get('localisation/232');
     *      $gb_country = API::get('localisation/gb');
     *      $gb_country = API::get('localisation/united-kingdom');
     *      $gb_country = API::get('localisation/country/232');
     *      $gb_country = API::get('localisation/country/gb');
     *      $gb_country = API::get('localisation/country/united-kingdom');
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
		if ($country_code != false)
		{
			// Get this country information.
			//
			$country = Country::find(function($query) use ($country_code)
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

			// Check if the country exists.
			//
			if (is_null($country))
			{
				// Invalid filter, return the message.
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

		// Get all the countries.
		//
		$countries = Country::all();

		// Return all the countries.
		//
		return new Response($countries);
	}


	// create a new country
	public function post_index()
	{
		echo 'create a country';
	}


	// edits a country
	public function put_index($country)
	{
		echo 'edit a country';
	}



    /**
     * --------------------------------------------------------------------------
     * Function: delete_index()
     * --------------------------------------------------------------------------
     *
     * Deletes multiple countries or deletes a given country using the provived
     * country code.
     *
     *  <code>
     *      $countries = API::delete('countries', $data); // not implemented
     *      $country   = API::delete('countries/:code');
     *  </code>
     *
     * @access   public
     * @param    string
     * @return   Response
     */
	public function delete_index($country)
	{
        // Check if the country exists.
        //
        try
        {
            $country = Country::find($country_code);
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
				'countries.id'  => 'ID'
			),
			'alias'    => array(
				'countries.id' => 'id'
			),
			'where'    => array(),
			'order_by' => array('countries.id' => 'desc'),
		);

		// lets get to total user count
		$count_total = Country::count();

		// get the filtered count
		// we set to distinct because a user can be in multiple groups
		$count_filtered = Country::all('countries.id', function($query) use ($defaults)
		{
			// sets the where clause from passed settings
			//$query = Table::count($query, $defaults);

			return $query;
				//->left_join('users_metadata', 'users.id', '=', 'users_metadata.user_id')
				//->left_join('users_groups', 'users.id', '=', 'users_groups.user_id')
				//->left_join('groups', 'users_groups.group_id', '=', 'groups.id')
				//->join('settings', 'settings.value', '=', 'users.status')
				//->where('settings.extension', '=', 'users')
				//->where('settings.type', '=', 'status');
		});
die;
		// set paging
		$paging = Table::prep_paging($count_filtered, 20);

		$items = User::all(function($query) use ($defaults, $paging)
		{
			list($query, $columns) = Table::query($query, $defaults, $paging);

			$columns[] = \DB::raw('GROUP_CONCAT(groups.name ORDER BY groups.name ASC SEPARATOR \',\') AS groups');

			return $query
				->select($columns)
				->join('settings', 'settings.value', '=', 'users.status')
				->where('settings.extension', '=', 'users')
				->where('settings.type', '=', 'status');

		});

		$items = ($items) ?: array();

		return new Response(array(
			'columns'        => $defaults['select'],
			'rows'           => $items,
			'count'          => $count_total,
			'count_filtered' => $count_filtered,
			'paging'         => $paging,
		));
	}
}
