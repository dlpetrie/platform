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
 * Localisation > Languages > Admin Class
 * --------------------------------------------------------------------------
 * 
 * Languages management.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Admin_Languages_Controller extends Admin_Controller
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
        $this->active_menu('admin-languages');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Shows all the languages.
     *
     * @access   public
     * @return   mixed
     */
    public function get_index()
    {
        // Grab the datatable.
        //
        $datatable = API::get('localisation/languages/datatable', Input::get());

        // Prepare the array.
        //
        $data = array(
            'rows'             => $datatable['rows'],
            'default_language' => $datatable['default_language']
        );

        // If this is an ajax request, only return the body of the datatable.
        //
        if (Request::ajax())
        {
            return json_encode(array(
                'content'        => Theme::make('localisation::languages.partials.table', $data)->render(),
                'count'          => $datatable['count'],
                'count_filtered' => $datatable['count_filtered'],
                'paging'         => $datatable['paging']
            ));
        }

        // Show the page.
        //
        return Theme::make('localisation::languages.index', $data);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_create()
     * --------------------------------------------------------------------------
     *
     * Language creation page.
     *
     * @access   public
     * @return   mixed
     */
    public function get_create()
    {
        // Show the page.
        //
        return Theme::make('localisation::languages.create');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_create()
     * --------------------------------------------------------------------------
     *
     * Language creation form processing page.
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
     * Language editing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function get_view($language_code)
    {
        try
        {
            // Make the request.
            //
            $language = API::get('localisation/language/' . $language_code);
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

            // Redirect to the languages page.
            //
            return Redirect::to_admin('localisation/languages');
        }

        // Show the page.
        //
        return Theme::make('localisation::languages.view')->with('language', $language);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_view()
     * --------------------------------------------------------------------------
     *
     * Language editing form processing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function post_view($language_code = false)
    {
        try
        {
            // Are we creating a language ?
            //
            if ($language_code === false)
            {
                // Make the request.
                //
                $request = API::post('localisation/language', Input::get());
            }

            // We must be editing the language.
            //
            else
            {
                // Make the request.
                //
                $request = API::put('localisation/language/' . $language_code, Input::get());
            }

            // Get the language slug.
            //
            $language_slug = $request['slug'];

            // Set the success message.
            //
            Platform::messages()->success($request['message']);

            // Check what button we pressed.
            //
            if (Input::get('save'))
            {
                // Redirect to the language page.
                //
                return Redirect::to_admin('localisation/languages/view/' . $language_slug);
            }
            else
            {
                // Redirect to the languages page.
                //
                return Redirect::to_admin('localisation/languages');
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
     * Language deletion page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function get_delete($language_code)
    {
        try
        {
            // Get this language information.
            //
            $language = API::get('localisation/language/' . $language_code);
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

            // Redirect to the languages page.
            //
            return Redirect::to_admin('localisation/languages');
        }

        // Show the page.
        //
        return Theme::make('localisation::languages.delete')->with('language', $language);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_delete()
     * --------------------------------------------------------------------------
     *
     * Language deletion form processing page.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function post_delete($language_code)
    {
        try
        {
            // Make the request.
            //
            $request = API::delete('localisation/language/' . $language_code);

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

        // Redirect to the languages page.
        //
        return Redirect::to_admin('localisation/languages');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_default()
     * --------------------------------------------------------------------------
     *
     * Makes a language the default language by the system.
     *
     * @access   public
     * @param    mixed
     * @return   mixed
     */
    public function get_default($language_code)
    {
        try
        {
            // Make the request.
            //
            $request = API::put('localisation/language/default/' . $language_code);

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

        // Redirect to the languages page.
        //
        return Redirect::to_admin('localisation/languages');
    }
}
