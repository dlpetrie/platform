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
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/**
 * --------------------------------------------------------------------------
 * Themes > Admin Class
 * --------------------------------------------------------------------------
 *
 * Manage your website themes.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Themes_Admin_Themes_Controller extends Admin_Controller
{
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
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_frontend()
     * --------------------------------------------------------------------------
     *
     * Shows Frontend Themes
     *
     * @access   public
     * @return   View
     */
    public function get_index($type)
    {
        // Make sure we have the type.
        //
        $type = ( $type ?: 'frontend' );

        // Set the active menu.
        //
        $this->active_menu('admin-' . $type);

        try
        {
            // Get the themes.
            //
            $themes = API::get('themes/' . $type);
        }
        catch(APIClientException $e)
        {
            // Set the error message.
            //
            Platform::messages()->error($e->getMessage());

            // Set the other error messages.
            //
            foreach($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }

            // Redirect to the dashboard page.
            //
            return Redirect::to_admin();
        }

        // Show the page.
        //
        return Theme::make('themes::index')->with('type', $type)->with('themes', $themes);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_edit()
     * --------------------------------------------------------------------------
     *
     * Edit Themes with associated options.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   mixed
     */
    public function get_edit($type, $name)
    {
        // Set the active menu.
        //
        $this->active_menu('admin-' . ( $type == 'frontend'? 'frontend' : 'backend'));

        // Initialize the data array.
        //
        $data = array(
            'type' => $type,
            'name' => $name
        );

        // Get theme data.
        //
        try
        {
            // Merge the default theme info with the custom options.
            //
            $options       = array_get(API::get('themes/' . $type . '/' . $name . '/options'), 'options');
            $data['theme'] = API::get('themes/' . $type . '/' . $name);
            $data['theme']['options'] = array_replace_recursive(array_get($data, 'theme.options'), $options);
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

            // Redirect to the themes page.
            //
            return Redirect::to_admin('themes');
        }

        // Show the page.
        //
        return Theme::make('themes::edit', $data);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_edit()
     * --------------------------------------------------------------------------
     *
     * Processes post data for theme options.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Redirect
     */
    public function post_edit($type, $name)
    {
        // Prepare the data array.
        //
        $data = array(
            'status'  => Input::get('status', 1),
            'options' => Input::get('options', array())
        );

        try
        {
            // Update the theme data.
            //
            API::put('themes/' . $type . '/' . $name . '/options', $data);

            // Set the success message.
            //
            Platform::messages()->success(Lang::line('themes::messages.update.success', array('theme' => $type . '\\' . $name))->get());
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

        // Redirect to the theme page.
        //
        return Redirect::to_admin('themes/' . $type . '/edit/' . $name);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_activate()
     * --------------------------------------------------------------------------
     *
     * Activates a theme, just an alias.
     *
     * @access   public
     * @param    string
     * @param    array
     * @return   Redirect
     */
    public function get_activate($type, $theme)
    {
        return $this->post_activate($type, $theme);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_activate()
     * --------------------------------------------------------------------------
     *
     * Activates a theme.
     *
     * @access   public
     * @param    string
     * @param    array
     * @return   Redirect
     */
    protected function post_activate($type, $theme)
    {
        try
        {
            // Make the request.
            //
            API::put('themes/activate/' . $type . '/' . $theme);

            // Set the success message.
            //
            Platform::messages()->success(Lang::line('themes::messages.activate.success', array('theme' => $type . '\\' . $theme))->get());
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

        // Redirect to the themes page, based on the theme type.
        //
        return Redirect::to_admin('themes/' . $type);
    }
}
