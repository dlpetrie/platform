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
 * Localisation > Countries > Admin Class
 * --------------------------------------------------------------------------
 * 
 * Countries management.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Admin_Countries_Controller extends Admin_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: __construct()
     * --------------------------------------------------------------------------
     *
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        // Call parent.
        //
        parent::__construct();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: before()
     * --------------------------------------------------------------------------
     *
     * This function is called before the action is executed.
     *
     * @access   public
     * @return   void
     */
    public function before()
    {
        // Call parent.
        //
        parent::before();

        // Set the active menu.
        //
        $this->active_menu('admin-countries');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Shows all the countries.
     *
     * @access   public
     * @return   mixed
     */
    public function get_index()
    {
        // Grab the datatable.
        //
        $datatable = API::get('localisation/countries/datatable', Input::get());

        // Prepare the array.
        //
        $data = array(
            'rows'            => $datatable['rows'],
            'default_country' => $datatable['default_country']
        );

        // If this is an ajax request, only return the body of the datatable.
        //
        if (Request::ajax())
        {
            return json_encode(array(
                'content'        => Theme::make('localisation::countries.partials.table', $data)->render(),
                'count'          => $datatable['count'],
                'count_filtered' => $datatable['count_filtered'],
                'paging'         => $datatable['paging']
            ));
        }

        // Show the page.
        //
        return Theme::make('localisation::countries.index', $data);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_create()
     * --------------------------------------------------------------------------
     *
     * Country creation page.
     *
     * @access   public
     * @return   mixed
     */
    public function get_create()
    {
        // Show the page.
        //
        return Theme::make('localisation::countries.create');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_create()
     * --------------------------------------------------------------------------
     *
     * Country creation form processing page.
     *
     * @access   public
     * @return   mixed
     */
    public function post_create()
    {
        return $this->post_view();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_view()
     * --------------------------------------------------------------------------
     *
     * Country editing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function get_view($country_code)
    {
        try
        {
            // Make the request.
            //
            $country = API::get('localisation/country/' . $country_code);
        }
        catch (APIClientException $e)
        {
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }

            // Redirect to the countries page.
            //
            return Redirect::to_admin('localisation/countries');
        }

        // Show the page.
        //
        return Theme::make('localisation::countries.view')->with('country', $country);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_view()
     * --------------------------------------------------------------------------
     *
     * Country editing form processing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function post_view($country_code = false)
    {
        try
        {
            // Are we creating a country ?
            //
            if ($country_code === false)
            {
                // Make the request.
                //
                $request = API::post('localisation/country', Input::get());
            }

            // We must be editing the country.
            //
            else
            {
                // Make the request.
                //
                $request = API::put('localisation/country/' . $country_code, Input::get());
            }

            // Get the country slug.
            //
            $country_slug = $request['slug'];

            // Set the success message.
            //
            Platform::messages()->success($request['message']);

            // Check what button we pressed.
            //
            if (Input::get('save'))
            {
                // Redirect to the country page.
                //
                return Redirect::to_admin('localisation/countries/view/' . $country_slug);
            }
            else
            {
                // Redirect to the countries page.
                //
                return Redirect::to_admin('localisation/countries');
            }
        }
        catch (APIClientException $e)
        {
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }

            // Redirect to the previous page.
            //
            return Redirect::back()->with_input()->with_errors($e->errors());
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_delete()
     * --------------------------------------------------------------------------
     *
     * Country deletion page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function get_delete($country_code)
    {
        try
        {
            // Get this country information.
            //
            $country = API::get('localisation/country/' . $country_code);
        }
        catch (APIClientException $e)
        {
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }

            // Redirect to the countries page.
            //
            return Redirect::to_admin('localisation/countries');
        }

        // Show the page.
        //
        return Theme::make('localisation::countries.delete')->with('country', $country);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_delete()
     * --------------------------------------------------------------------------
     *
     * Country deletion form processing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function post_delete($country_code)
    {
        try
        {
            // Make the request.
            //
            $request = API::delete('localisation/country/' . $country_code);

            // Set the success message.
            //
            Platform::messages()->success($request['message']);
        }
        catch (APIClientException $e)
        {
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }
        }

        // Redirect to the countries page.
        //
        return Redirect::to_admin('localisation/countries');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_default()
     * --------------------------------------------------------------------------
     *
     * Makes a country the default country by the system.
     *
     * @access   public
     * @param    mixed
     * @return   mixed
     */
    public function get_default($country_code)
    {
        try
        {
            // Make the request.
            //
            $request = API::put('localisation/country/default/' . $country_code);

            // Set the success message.
            //
            Platform::messages()->success($request['message']);
        }
        catch (APIClientException $e)
        {
            echo $e->getMessage();
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }
        }

        // Redirect to the countries page.
        //
        return Redirect::to_admin('localisation/countries');
    }
}
