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

class Themes_Admin_Themes_Controller extends Admin_Controller
{
	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-themes');
	}

	/**
	 * Shows Frontend Themes
	 *
	 * @return  View
	 */
	public function get_frontend()
	{
		$data = $this->theme_data('frontend');
		$this->active_menu('admin-frontend');

		return Theme::make('themes::index', $data);
	}

	/**
	 * Shows Backend Themes
	 *
	 * @return  View
	 */
	public function get_backend()
	{
		$data = $this->theme_data('backend');
		$this->active_menu('admin-backend');

		return Theme::make('themes::index', $data);
	}

	/**
	 * Edit Themes with associated options
	 *
	 * @param   string  $type
	 * @param   string  $name
	 * @return  View
	 */
	public function get_edit($type, $name)
	{
		// Set menu active states
		if ($type == 'frontend')
		{
			$this->active_menu('admin-frontend');
		}
		else
		{
			$this->active_menu('admin-backend');
		}

		$data = array(
			'type' => $type,
			'name' => $name,
		);

		// Get theme data
		try
		{
			// Merge the default theme info with the custom options
			$data['theme'] = API::get('themes/'.$type.'/'.$name);
			$options       = API::get('themes/'.$type.'/'.$name.'/options');
			$options       = array_get($options, 'options');
			$data['theme']['options'] = array_replace_recursive(array_get($data, 'theme.options'), $options);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Theme::make('themes::edit', $data);
	}

	/**
	 * Processes post data for theme options
	 *
	 * @param   string  $type
	 * @param   string  $name
	 * @return  Redirect
	 */
	public function post_edit($type, $name)
	{
		$data = array(

			// Currently, status is never passed
			// through by the input
			'status'  => Input::get('status', 1),
			'options' => Input::get('options', array()),
		);

		try
		{
			API::put('themes/'.$type.'/'.$name.'/options', $data);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to_secure(ADMIN.'/themes/edit/'.$type.'/'.$name);
	}


	/**
	 * Activates a theme
	 *
	 * @param   string  $type
	 * @return  array   $theme
	 */
	protected function post_activate($type, $theme)
	{
		try
		{
			API::post('settings', array(
				'settings' => array(
					'values' => array(
						'extension' => 'themes',
						'type'      => 'theme',
						'name'      => Input::get('type'),
						'value'     => Input::get('theme'),
					),

					// validation
					'validation' => array(
						'name'  => 'required',
						'value' => 'required',
					),

					// labels
					'labels' => array(
						'name' => 'Theme'
					),
				),
			));
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to_secure(ADMIN.'/themes/'.$type);
	}

	/**
	 * Gets all theme data necessary for views
	 *
	 * @param   string  $type
	 * @return  array   $data
	 */
	protected function theme_data($type)
	{
		try
		{
			$themes = API::get('themes/'.$type);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			$themes = array();
		}

		$active = Platform::get('themes.theme.'.$type, 'default');

		// Set some fallback data
		$data = array(

			// The type of theme
			'type' => $type,

			// If we have an existing active theme
			'exists' => false,

			// Mimmick the data we
			// get back from the API
			// so the view exists.
			'active' => array(
				'name' => Str::title($active),
			),

			// Inactive themes is everything
			// else. We'll override this soon
			'inactive' => $themes,
		);

		// Loop through themes
		foreach ($themes as $index => $theme)
		{
			// If it's the active theme
			if ($theme['theme'] === $active)
			{
				// We have an active theme
				$data['exists'] = true;

				// Set it in the data array
				$data['active'] = $theme;

				// Remove the inactive one
				array_forget($data, 'inactive.'.$index);
			}
		}

		return $data;
	}

}
