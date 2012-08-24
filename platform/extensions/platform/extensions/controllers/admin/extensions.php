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
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

class Extensions_Admin_Extensions_Controller extends Admin_Controller
{

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-extensions');
	}

	public function get_index()
	{
		try
		{
			$installed = API::get('extensions', array(
				'filter' => 'installed',
			));

			$uninstalled = API::get('extensions', array(
				'filter' => 'uninstalled',
			));
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to_secure(ADMIN);
		}

		$data = array(
			'installed'   => $installed,
			'uninstalled' => $uninstalled,
		);

		return Theme::make('extensions::index', $data);
	}

	public function get_install($slug)
	{
		try
		{
			API::put('extensions/'.$slug, array(
				'installed' => true,
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

		return Redirect::to_secure(ADMIN.'/extensions');
	}

	public function get_uninstall($slug)
	{
		try
		{
			API::put('extensions/'.$slug, array(
				'installed' => false,
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

		return Redirect::to_secure(ADMIN.'/extensions');
	}

	public function get_enable($slug)
	{
		try
		{
			API::put('extensions/'.$slug, array(
				'installed' => true,
				'enabled'   => true,
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

		return Redirect::to_secure(ADMIN.'/extensions');
	}

	public function get_disable($slug)
	{
		try
		{
			API::put('extensions/'.$slug, array(
				'installed' => true,
				'enabled'   => false,
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

		return Redirect::to_secure(ADMIN.'/extensions');
	}

	public function get_update($slug)
	{
		try
		{
			API::put('extensions/'.$slug, array(
				'update' => true,
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

		return Redirect::to_secure(ADMIN.'/extensions');
	}

}
