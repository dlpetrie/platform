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

use Platform\Users\User;
use Sentry\SentryException;

class Users_API_Users_Controller extends API_Controller
{

	/**
	 * Returns an array of users by the
	 * given filters or a single user
	 *
	 *	<code>
	 *		$users = API::get('users');
	 *		$user  = API::get('users/:id');
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function get_index($id = false)
	{
		$config = Input::get() + array(
			'select'   => array('users.id', 'users.email', 'users_metadata.*', 'users.status', \DB::raw('GROUP_CONCAT(groups.name ORDER BY groups.name ASC SEPARATOR \',\') AS groups')
			),
			'where'    => array(),
			'order_by' => array(),
			'take'     => null,
			'skip'     => 0,
		);

		// No ID? Return all users
		if ($id == false)
		{

			$users = User::find_custom($config['select'], $config['where'], $config['order_by'], $config['take'], $config['skip']);

			foreach ($users as &$user)
			{
				$user->groups = explode(',', $user->groups);
			}

			return new Response($users);
		}

		$config['take']  = 1;
		$config['where'] = array('users.id', '=', $id);

		$users = User::find_custom($config['select'], $config['where'], $config['order_by'], $config['take'], $config['skip']);

		if (empty($users))
		{
			return new Response(Lang::line('users::messages.users.errors.does_not_exist', array(
				'id' => $id,
			))->get(), API::STATUS_NOT_FOUND);
		}

		$user = $users[0];
		$user->groups = explode(',', $user->groups);
		return new Response($user);
	}

	/**
	 * Creates a user
	 *
	 *	<code>
	 *		API::post('users', $data);
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_index()
	{
		// Create a user
		$user = new User(Input::get());

		// Save user
		try
		{
			// Save the user
			if ($user->save())
			{
				return new Response($user, API::STATUS_CREATED);
			}
			else
			{
				return new Response(array(
					'message' => Lang::line('users::messages.users.create.error')->get(),
					'errors'  => ($user->validation()->errors->has()) ? $user->validation()->errors->all() : array(),
					), ($user->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Updates a given user by the
	 * provided ID
	 *
	 *	<code>
	 *		API::put('users/:id', $data);
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function put_index($id)
	{
		// Update a user
		$user = new User(array_merge(array(
			'id' => $id,
		), Input::get()));

		// Save user
		try
		{
			// Save the user
			if ($user->save())
			{
				return new Response($user);
			}
			else
			{
				return new Response(array(
					'message' => Lang::line('users::messages.users.update.error')->get(),
					'errors'  => ($user->validation()->errors->has()) ? $user->validation()->errors->all() : array(),
				), ($user->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Deletes a user by the
	 * given ID
	 *
	 *	<code>
	 *		API::delete('users/:id');
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function delete_index($id)
	{
		$user = User::find($id);

		if ($user === null)
		{
			return new Response(array(
				'message' => Lang::line('users::messages.users.general.does_not_exist')->get()
			), API::STATUS_NOT_FOUND);
		}

		try
		{
			if ($user->delete())
			{
				return new Response(null, API::STATUS_NO_CONTENT);
			}

			return new Response(array(
				'message' => "An error occured while deleting the user [$id]",
				'errors'  => ($user->validation()->errors->has()) ? $user->validation()->errors->all() : array(),
			), ($user->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Returns fields required for a
	 * Platform.table
	 *
	 * @return  Response
	 */
	public function get_datatable()
	{
		$defaults = array(
			'select'   => array(
				'users.id'       => Lang::line('users::table.users.id')->get(),
				'first_name'     => Lang::line('users::table.users.first_name')->get(),
				'last_name'      => Lang::line('users::table.users.last_name')->get(),
				'email'          => Lang::line('users::table.users.email')->get(),
				'groups.name'    => Lang::line('users::table.users.groups')->get(),
				'settings.name'  => Lang::line('users::table.users.status')->get(),
				'created_at'     => Lang::line('general.created_at')->get(),
			),
			'alias'    => array(
				'users.id'      => 'id',
				'groups.name'   => 'groups',
				'settings.name' => 'status'
			),
			'where'    => array(),
			'order_by' => array('users.id' => 'desc'),
		);

		// lets get to total user count
		$count_total = User::count();

		// get the filtered count
		// we set to distinct because a user can be in multiple groups
		$count_filtered = User::count_distinct('users.id', function($query) use ($defaults)
		{
			// sets the where clause from passed settings
			$query = Table::count($query, $defaults);

			return $query
				->left_join('users_metadata', 'users.id', '=', 'users_metadata.user_id')
				->left_join('users_groups', 'users.id', '=', 'users_groups.user_id')
				->left_join('groups', 'users_groups.group_id', '=', 'groups.id')
				->join('settings', 'settings.value', '=', 'users.status')
				->where('settings.extension', '=', 'users')
				->where('settings.type', '=', 'status');
		});

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

	/**
	 * Registers a user (but doesn't)
	 * activate them.
	 *
	 *	<code>
	 *		POST('users/register', $data);
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_register()
	{
		$user_data = Input::get() + array(
			'register' => true,
		);

		$user = new User($user_data);

		// Save user
		try
		{
			$result = $user->save();

			if ($result['id'])
			{
				// Send email
				$hash = $result['hash'];

				// Get the Swift Mailer instance
				Bundle::start('swiftmailer');
				$mailer = IoC::resolve('mailer');

				// Get email
				$body = File::get(path('public').'platform/emails'.DS.'register.html');

				// Replacements
				$replacements = array(
					'/{{SITE_TITLE}}/'      => Platform::get('settings.general.title'),
					'/{{ACTIVATION_LINK}}/' => URL::to_secure('activate/'.$hash),
				);

				$body = preg_replace(array_keys($replacements), array_values($replacements), $body);

				// Construct the message
				$message = Swift_Message::newInstance(Platform::get('settings.general.title').'        - Activate Account')
				           ->setFrom(Platform::get('settings.general.email'), Platform::get('settings.general.title'))
				           ->setTo(Input::get('email'))
				           ->setBody($body,'text/html');

				// Send the email
				$mailer->send($message);

				return new Response($user, API::STATUS_CREATED);
			}
			else
			{
				return new Response(array(
					'message' => Lang::line('user::messages.users.create.error')->get(),
					'errors'  => ($user->validation()->errors->has()) ? $user->validation()->errors->all() : array(),
				), ($user->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);

			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Activates a user by providing
	 * the user's email and the activation
	 * code.
	 *
	 *	<code>
	 *		API::post('users/activate', array(
	 *			'email' => ':email',
	 *			'code'  => ':code',
	 *		));
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_activate()
	{
		try
		{
			if ($user = Sentry::activate_user(Input::get('email'), Input::get('code')))
			{
				$user = User::find($user->id);

				return new Response($user);
			}

			return new Response(array(
				'message' => 'Could not activate user.',
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Logs a user in.
	 *
	 *	<code>
	 *		API::post('users/login', array(
	 *			'email'    => ':email',
	 *			'password' => ':password',
	 *		));
	 *	</code>
	 *
	 * @return  Response
	 */
	public static function post_login()
	{
		// Log the user out
		Sentry::logout();

		try
		{
			// Log the user in
			if (Sentry::login(Input::get('email'), Input::get('password'), Input::get('remember')))
			{
				return new Response(array(
					'is_admin' => Sentry::user()->has_access('is_admin'),
				));
			}

			return new Response(array(
				'message' => Lang::line('users::messages.auth.invalid_login')->get(),
			), API::STATUS_UNAUTHORIZED);
		}
		catch (SentryException $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Logs the current user out.
	 *
	 *	<code>
	 *		API::post('users/logout');
	 *	</code>
	 *
	 * @return  Response
	 */
	public function get_logout()
	{
		Sentry::logout();

		return new Response(null, API::STATUS_NO_CONTENT);
	}

	/**
	 * Sends an email out to reset an
	 * account password.
	 *
	 *	<code>
	 *		API::post('users/reset_password', array(
	 *			'email' => ':email',
	 *			'password' => ':password',
	 *		));
	 *
	 * @return  Response
	 */
	public function post_reset_password()
	{
		try
		{
			if ($reset = Sentry::reset_password(Input::get('email'), Input::get('password')))
			{
				// Start up swiftmailer
				Bundle::start('swiftmailer');

				// Get the Swift Mailer instance
				$mailer = IoC::resolve('mailer');

				$link = URL::to(ADMIN.'/reset_password_confirm/'.$reset['link']);

				// Get email
				$body = File::get(path('public').'platform'.DS.'emails'.DS.'reset_password.html');

				// Replacements
				$replacements = array(
					'/{{SITE_TITLE}}/' => Platform::get('settings.general.title'),
					'/{{RESET_LINK}}/' => $link,
				);

				$body = preg_replace(array_keys($replacements), array_values($replacements), $body);

				// Construct the message
				$message = Swift_Message::newInstance()
				         ->setSubject(Platform::get('settings.site.title').' - Password Reset')
				         ->setFrom(Platform::get('settings.site.email'), Platform::get('settings.site.title'))
				         ->setTo(Input::get('email'))
				         ->setBody($body,'text/html');

				// Send the email
				$mailer->send($message);

				return new Response(null, API::STATUS_NO_CONTENT);
			}

			return new Response(array(
				'message' => Lang::line('users::messages.auth.password_error')->get(),
			), API::STATUS_BAD_REQUEST);
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}

	}

	/**
	 * Confirms the password reset.
	 *
	 *	<code>
	 *		API::post('users/reset_password_confirm', array(
	 *			'email' => ':email',
	 *			'password' => ':password',
	 *		));
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_reset_password_confirm()
	{
		if ($reset = Sentry::reset_password_confirm(Input::get('email'), Input::get('password')))
		{
			return new Response(null, API::STATUS_NO_CONTENT);
		}

		return new Response(array(
			'message' => Lang::line('users::users.auth.password_confirm_error')->get(),
		), API::STATUS_UNPROCESSABLE_ENTITY);
	}

}
