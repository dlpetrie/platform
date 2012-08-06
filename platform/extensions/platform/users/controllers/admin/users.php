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

class Users_Admin_Users_Controller extends Admin_Controller
{
	/**
	 * Whitelisted auth routes.
	 *
	 * @var  array
	 */
	protected $whitelist = array(
		'login', 'logout', 'reset_password',
		'reset_password_confirm',
	);

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-users-list');
	}

	/**
	 * Admin Users Dashboard / Base View
	 *
	 * @return  View
	 */
	public function get_index()
	{
		// Grab our datatable
		$datatable = API::get('users/datatable', Input::get());

		$data = array(
			'columns' => $datatable['columns'],
			'rows'    => $datatable['rows'],
		);

		// If this was an ajax request, only return the body of the datatable
		if (Request::ajax())
		{
			return json_encode(array(
				"content"        => Theme::make('users::user.partials.table_users', $data)->render(),
				"count"          => $datatable['count'],
				"count_filtered" => $datatable['count_filtered'],
				"paging"         => $datatable['paging'],
			));
		}

		return Theme::make('users::user.index', $data);
	}

	/**
	 * Create User Form
	 *
	 * @return  View
	 */
	public function get_create()
	{
		return Theme::make('users::user.create');
	}

	/**
	 * Create User Form Processing
	 *
	 * @return  Redirect
	 */
	public function post_create()
	{
		return $this->post_edit();
	}

	/**
	 * Edit User Form
	 *
	 * @param   int  user id
	 * @return  View
	 */
	public function get_edit($id)
	{
		$data = array('id' => $id);

		return Theme::make('users::user.edit', $data);
	}

	/**
	 * Edit User Form Processing
	 *
	 * @return  Redirect
	 */
	public function post_edit($id = false)
	{
		// Initialize data array
		$data = array(
			'email'                 => Input::get('email'),
			'password'              => Input::get('password'),
			'password_confirmation' => Input::get('password_confirmation'),
			'groups'                => Input::get('groups'),
			'metadata'              => array(
				'first_name' => Input::get('first_name'),
				'last_name'  => Input::get('last_name'),
			)
		);

		echo '<pre>';

		try
		{
			if ($id)
			{
				$user = API::put('users/'.$id, $data);
			}
			else
			{
				$user = API::post('users', $data);
			}
		}
		catch (APIClientException $e)
		{
			throw $e;
		}

		die();

		// update user
		$update_user = API::post('users/update', $data);

		if ($update_user['status'])
		{
			// user was updated - set success and redirect back to admin/users
			Platform::messages()->success($update_user['message']);
			return Redirect::to_secure(ADMIN.'/users');
		}
		else
		{
			// there was an error updating the user - set errors
			Platform::messages()->error($update_user['message']);
			return Redirect::to_secure(ADMIN.'/users/edit/'.$id)->with_input();
		}
	}

	/**
	 * Delete a user - AJAX request
	 *
	 * @param   int     user id
	 * @return  object  json object
	 */
	public function get_delete($id)
	{
		// delete the user
		$delete_user = API::post('users/delete', array('id' => $id));

		if ($delete_user['status'])
		{
			// user was edited - set success and redirect back to admin/users
			Platform::messages()->success($delete_user['message']);
			return Redirect::to_secure(ADMIN.'/users');
		}
		else
		{
			// there was an error editing the user - set errors
			Platform::messages()->error($delete_user['message']);
			return Redirect::to_secure(ADMIN.'/users');
		}
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
			Platform::messages()->error('A user Id is required to update permissions.');
			return Redirect::to_secure(ADMIN.'/users');
		}

		$permissions = Input::get();
		$rules = Sentry\Sentry_Rules::fetch_rules();

		$update_permissions = array();
		foreach ($rules as $rule)
		{
			$slug = \Str::slug($rule, '_');

			if (array_key_exists($slug, $permissions))
			{
				$update_permissions[$rule] = 1;
			}
			else
			{
				$update_permissions[$rule] = '';
			}
		}

		// initialize data array
		$data = array(
			'id'          => $id,
			'permissions' => $update_permissions
		);

		// update user
		$update_user = API::post('users/update', $data);

		if ($update_user['status'])
		{
			// user was updated - set success and redirect back to admin users
			Platform::messages()->success($update_user['message']);
			return Redirect::to_secure(ADMIN.'/users');
		}
		else
		{
			// there was an error updating the user - set errors
			Platform::messages()->error($update_user['message']);
			return Redirect::to_secure(ADMIN.'/users/edit/'.$id)->with_input();
		}
	}

}
