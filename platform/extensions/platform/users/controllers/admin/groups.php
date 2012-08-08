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

class Users_Admin_Groups_Controller extends Admin_Controller
{

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-groups-list');
	}

	/**
	 * Admin User Groups Dashboard / Base View
	 *
	 * @return  View
	 */
	public function get_index()
	{
		// Get all the input
		$options = Input::get();

		// Grab our table data from the user groups api
		$datatable = API::get('users/groups/datatable', $options);

		// Format data for passing
		$data = array(
			'columns' => $datatable['columns'],
			'rows'    => $datatable['rows'],
		);

		// If this was an ajax request, only return the body of the table
		if (Request::ajax())
		{
			$data = (json_encode(array(
				"content"        => Theme::make('users::group.partials.table_groups', $data)->render(),
				"count"          => $datatable['count'],
				"count_filtered" => $datatable['count_filtered'],
				"paging"         => $datatable['paging'],
			)));

			return $data;
		}

		return Theme::make('users::group.index', $data);
	}

	/**
	 * Create Group
	 *
	 * @return  View
	 */
	public function get_create()
	{
		return Theme::make('users::group.create', $data = array());
	}

	/**
	 * Create Group Form Processing
	 *
	 * @return  redirect
	 */
	public function post_create()
	{
		return $this->post_edit();
	}

	/**
	 * Edit Group Form
	 *
	 * @param   int  group id
	 * @return  View
	 */
	public function get_edit($id = null)
	{
		return Theme::make('users::group.edit', array('id' => $id));
	}

	/**
	 * Edit Group Form Processing
	 *
	 * @return  Redirect
	 */
	public function post_edit($id = false)
	{
		$data = array(
			'name' => Input::get('name'),
		);

		try
		{
			if ($id)
			{
				$user = API::put('users/groups/'.$id, $data);
			}
			else
			{
				$user = API::post('users/groups', $data);
			}
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to_secure(ADMIN.'/users/groups/'.(($id) ? 'edit/'.$id : 'create'))->with_input();
		}

		return Redirect::to_secure(ADMIN.'/users/groups');
	}

	/**
	 * Delete a group - AJAX request
	 *
	 * @param   int     group id
	 * @return  object  json object
	 */
	public function get_delete($id)
	{

		try
		{
			API::delete('users/groups/'.$id);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to(ADMIN.'/users/groups');
	}

	/**
	 * Process permission post
	 *
	 * @return  Redirect
	 */
	public function post_permissions($id)
	{
		if ( ! $id)
		{
			Platform::messages()->error('A group Id is required to update permissions.');
			return Redirect::to_secure(ADMIN.'/users/groups');
		}

		$permissions = Input::get();
		$rules       = Sentry\Sentry_Rules::fetch_rules();

		$update_permissions = array();

		foreach ($rules as $rule)
		{
			$slug = Str::slug($rule, '_');

			if (array_key_exists($slug, $permissions))
			{
				$update_permissions[$rule] = 1;
			}
			else
			{
				$update_permissions[$rule] = '';
			}
		}

		$data = array(
			'permissions' => $update_permissions,
		);

		try
		{
			API::put('users/groups/'.$id, $data);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to_secure(ADMIN.'/users/groups/edit/'.$id)->with_input();
		}

		return Redirect::to_secure(ADMIN.'/users/groups');
	}

}
