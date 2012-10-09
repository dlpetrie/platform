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
use Platform\Menus\Menu,
    Platform\Settings\Model\Setting;


/**
 * --------------------------------------------------------------------------
 * Settings > Admin Class
 * --------------------------------------------------------------------------
 *
 * Settings to manage your website settings.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Settings_Admin_Settings_Controller extends Admin_Controller
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
        $this->active_menu('admin-settings');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * The main page of the settings extension.
     *
     * @access   public
     * @return   View
     */
    public function get_index()
    {
        // Initiate an empty array.
        //
        $settings = array();

        // Get all the settings from the database.
        //
        foreach (API::get('settings', array('organize' => true)) as $setting)
        {
            // Populate the extension name of each setting.
            //
            foreach ($setting as $data)
            {
                $settings[ $data['extension'] ][ $data['type'] ][ $data['name'] ] = $data['value'];
            }
        }

        // Show the page.
        //
        return Theme::make('settings::index')->with('settings', $settings);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_index()
     * --------------------------------------------------------------------------
     *
     * Form processing.
     *
     * @access   public
     * @return   Redirect
     */
    public function post_index()
    {
        // Initiate an empty array.
        //
        $settings = array();

        // Loop through the submited data.
        //
        foreach (Input::get() as $field => $value)
        {
            // Extension field shall not pass !
            //
            if ($field === 'extension')
            {
                continue;
            }

            // Find the type and name for the respective field.
            // If a field contains a ':', then a type was given.
            //
            if (strpos($field, ':') !== false)
            {
                list($type, $name) = explode(':', $field);
            }
            else
            {
                $type = '';
                $name = $field;
            }

            // Get this extension name.
            //
            $extension = Input::get('extension', 'settings');

            // Set validation if the field doesn't exist.
            //
            $validation = null;

            // Check if this widget has validation rules.
            //
            $widget = 'Platform\\' . ucfirst($extension) . '\\Widgets\\Settings';
            if (isset($widget::$validation) and array_key_exists($name, $widget::$validation))
            {
                // Get the rules.
                //
                $validation = array($name => array_get($widget::$validation, $name));
            }

            // Set the values.
            //
            $settings[] = array(
                'extension'  => $extension,
                'type'       => $type,
                'name'       => $name,
                'value'      => $value,
                'validation' => $validation
            );
        }

        try
        {
            // Make the API request.
            //
            $request = API::put('settings', array( 'settings' => $settings ));

            // If we have fields that were updated with success.
            //
            if ($updated = array_get($request, 'updated'))
            {
                // Loop through each updated setting.
                //
                foreach ($updated as $setting)
                {
                    // Set the success message.
                    //
                    Platform::messages()->success(Lang::line('settings::message.success', array('setting' => $setting)));
                }
            }

            // If we have fields that were not updated with success.
            //
            if ($errors = array_get($request, 'errors'))
            {
                // Loop through the error messages.
                //
                foreach ($errors as $error)
                {
                    // Set the error message.
                    //
                    Platform::messages()->error($error);
                }
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
        }

        // Redirect back to the settings page.
        //
        return Redirect::to_secure(ADMIN . '/settings');
        #return Redirect::to_admin('settings');
    }
}
