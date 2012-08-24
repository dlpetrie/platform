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

class Users_Auth_Controller extends Public_Controller
{

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('main-login');
	}

	public function get_register()
	{
		$this->active_menu('main-register');
		return Theme::make('users::auth/register');
	}

	public function post_register()
	{
		$data = array(
			'email'                 => Input::get('email'),
			'email_confirmation'    => Input::get('email_confirmation'),
			'password'              => Input ::get('password'),
			'password_confirmation' => Input::get('password_confirmation'),
			'metadata' => array(
				'first_name' => Input::get('first_name'),
				'last_name'  => Input::get('last_name'),
			),
		);

		try
		{
			API::post('users/register', $data);

			return Redirect::to('login');
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to('register')->with_input();
		}
	}

	public function get_activate($email, $code)
	{
		try
		{
			API::post('users/activate', array(
				'email' => $email,
				'code'  => $code,
			));

			Platform::messages()->success('Successfully activated user.');
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to('login');
	}

	/**
	 * Login Form
	 *
	 * @return  View
	 */
	public function get_login()
	{
		API::get('users/logout');
		$this->active_menu('main-login');
		return Theme::make('users::auth/login');
	}

	/**
	 * Login Form Processing
	 */
	public function post_login()
	{
		if (Request::ajax())
		{
			$response  = array();
			$http_code = API::STATUS_OK;
		}

		try
		{
			$result = API::post('users/login', array(
				'email'    => Input::get('email'),
				'password' => Input::get('password'),
			));

			// If we're admin
			if (array_get($result, 'is_admin'))
			{
				if ( ! Request::ajax())
				{
					return Redirect::to_secure(ADMIN);
				}
				else
				{
					$response['redirect'] = URL::to_secure(ADMIN);
				}
			}
			else
			{
				if ( ! Request::ajax())
				{
					return Redirect::to('');
				}

				$response['redirect'] = URL::to('');
			}

			// Success message
			$response['message'] = Lang::line('users::messages.auth.success')->get();
		}
		catch (APIClientException $e)
		{
			if ( ! Request::ajax())
			{
				Platform::messages()->error($e->getMessage());

				foreach ($e->errors() as $error)
				{
					Platform::messages()->error($error);
				}

				return Redirect::to('login')->with_input();
			}

			// Build our reponse array
			$response['message'] = $e->getMessage();
			$http_code           = $e->getCode();
		}

		// To get this far we're in an AJAX response
		return new Response(json_encode($response), $http_code);
	}

	/**
	 * Log user out
	 *
	 * @return  Redirect
	 */
	 public function get_logout()
	 {
	 	try
	 	{
	 		API::get('users/logout');
	 	}
	 	catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to((Session::get('last_url')) ?: URL::to(''));
	 }

	/**
	 * Reset Password Form
	 *
	 * @return View
	 */
	public function get_reset_password()
	{
		$this->active_menu('main-login');
		return Theme::make('users::auth/reset_password');
	}

	/**
	 * Reset Password Processing
	 *
	 * @return object  json
	 */
	public function post_reset_password()
	{
		if (Request::ajax())
		{
			$response  = array();
			$http_code = API::STATUS_OK;
		}

		try
		{
			API::post('users/reset_password', array(
				'email'    => Input::get('email'),
				'password' => Input::get('password'),
			));

			if ( ! Request::ajax())
			{
				Platform::messages()->success(Lang::line('users::messages.auth.password_success')->get());

				return Redirect::to_secure('login');
			}
			else
			{
				$response['message'] = Lang::line('users::messages.auth.password_success')->get();
			}
		}
		catch (APIClientException $e)
		{
			if ( ! Request::ajax())
			{
				Platform::messages()->error($e->getMessage());

				foreach ($e->errors() as $error)
				{
					Platform::messages()->error($error);
				}

				return Redirect::to((Session::get('last_url')) ?: URL::to(''));
			}
			else
			{
				$response['message'] = $e->getMessage();
				$http_code           = $e->getCode();
			}
		}

		// To get this far we're in an AJAX response
		return new Response(json_encode($response), $http_code);
	}

	/**
	 * Reset Password Confirmation
	 *
	 * @param   string  users encoded email
	 * @param   string  encoded confirmation hash
	 * @return  View
	 */
	public function get_reset_password_confirm($email = null, $password = null)
	{
		try
		{
			API::post('users/reset_password_confirm', array(
				'email'    => $email,
				'password' => $password,
			));

			Platform::messages()->success(Lang::line('users::messages.auth.password_confirm_success')->get());

			return Redirect::to_secure('login');
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to_secure('reset_password');
		}
	}

}


