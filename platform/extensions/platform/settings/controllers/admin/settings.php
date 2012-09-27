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


use Platform\Menus\Menu;
use Platform\Settings\Model\Setting;


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

	protected $validation = array(
		// general settings
		'general' => array(
			'site:name'  => 'required',
			'site:email' => 'required|email'
		)
	);


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
		// Get all the settings from the database.
		//
		foreach ( API::get('settings', array('organize' => true)) as $setting )
		{
			// Populate the extension name of each setting.
			//
			foreach ( $setting as $data )
			{
				$settings[ $data['extension'] ][ $data['type'] ][ $data['name'] ] = $data['value'];
			}
		}

		// Show the page.
		//
		return Theme::make('settings::index')->with('settings', $settings);
	}









	public function post_general()
	{
		$post = Input::get();

		$settings = array();
		foreach ($post as $field => $value)
		{
			// Find the type and name for the respective field.
			// If a field contains a ':', then a type was given
			if (strpos($field, ':') !== false)
			{
				list($type, $name) = explode(':', $field);
			}
			else
			{
				$type = '';
				$name = $field;
			}

			// set the values
			$values = array(
				'extension' => 'settings',
				'type'      => $type,
				'name'      => $name,
				'value'     => $value,
			);

			// set validation for the field if it exists
			$validation = null;
			if (array_key_exists($field, $this->validation['general']))
			{
				if (is_array($this->validation['general'][$field]))
				{
					$validation = $this->validation['general'][$feild];
				}
				else
				{
					$validation = array('value' => $this->validation['general'][$field]);
				}
			}

			$settings[] = array(
				'values'     => $values,
				'validation' => $validation
			);
		}

		try
		{
			$updated = Api::post('settings', array(
				'settings' => $settings,
			));

			if (is_array($updated) and count($updated) > 0)
			{
				foreach ($updated as $setting)
				{
					Platform::messages()->success(Lang::line('settings::messages.success.update', array(
						'setting' => $setting,
					)));
				}
			}
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to_secure(ADMIN.'/settings/general');
	}

}
