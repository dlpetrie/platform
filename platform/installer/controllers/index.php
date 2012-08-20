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

use Installer\Installer;

class Installer_Index_Controller extends Base_Controller
{

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();

		// Setup CSS
		Asset::add('bootstrap', 'platform/installer/css/bootstrap.min.css');
		Asset::add('installer', 'platform/installer/css/installer.css');

		// Setup JS
		Asset::add('jquery', 'platform/installer/js/jquery.js');
		Asset::add('url', 'platform/installer/js/url.js');
		Asset::add('bootstrap', 'platform/installer/js/bootstrap.js', array('jquery'));
		Asset::add('validation', 'platform/installer/js/validate.js', array('jquery'));
		Asset::add('tempo', 'platform/installer/js/tempo.js', array('jquery'));
		Asset::add('installer', 'platform/installer/js/installer.js', array('jquery'));

		// If we're already installed
		if (Platform::is_installed() and URI::segment(2) !== 'step_4')
		{
			Redirect::to('installer/step_4')->send();
			exit;
		}

		// If we're not prepared for installation
		if ( ! Installer::is_prepared() and ! in_array(URI::segment(2, 'step_1'), array('step_1', 'permissions')))
		{
			Redirect::to('installer')->send();
			exit;
		}
	}

	/**
	 * Alias for step 1.
	 *
	 * @return  View
	 */
	public function get_index()
	{
		return $this->get_step_1();
	}

	/**
	 * Returns the first step of the installation process.
	 *
	 * This step is a pre-installation checklist to make sure
	 * the system is prepared to be installed.
	 *
	 * @return  View
	 */
	public function get_step_1()
	{
		// Prepare our database
		Installer::prepare();

		// Get an array of permissions
		$data['permissions'] = Installer::permissions();

		return View::make('installer::step_1', $data);
	}

	/**
	 * Not used just now. Developers may
	 * attach a license agreement or other form
	 * data to get_step_1() and process it here
	 *
	 * @return  Redirect
	 */
	public function post_step_1()
	{
		return Redirect::to('installer/step_2');
	}

	/**
	 * Returns the second step of the installation process.
	 *
	 * This step is we check the database.
	 *
	 * @return  View
	 */
	public function get_step_2()
	{
		// Initialize data array
		$data = array(
			'driver'   => null,
			'host'     => null,
			'username' => null,
			'database' => null,
		);

		// check for session data
		$credentials = Installer::get_step_data(2, array());
		foreach ($credentials as $values => $value)
		{
			$data[$values] = $value;
		}

		return View::make('installer::step_2')->with('drivers', Installer::database_drivers())->with('credentials', $data);
	}

	/**
	 * Stores the database credentials to the session.
	 *
	 * @return  Redirect
	 */
	public function post_step_2()
	{
		Installer::remember_step_data(2, Input::get());

		return Redirect::to('installer/step_3');
	}

	/**
	 * Returns the third step of the installation process.
	 *
	 * This step is where we put admin credentials in.
	 *
	 * @return  View
	 */
	public function get_step_3()
	{
		return View::make('installer::step_3');
	}

	/**
	 * Stores the admin credentials to the session
	 *
	 * @return  Redirect
	 */
	public function post_step_3()
	{
		Installer::remember_step_data(3, Input::get());

		return Redirect::to('installer/install');
	}

	/**
	 * Actually does the install process.
	 *
	 * @return  Redirect
	 */
	public function get_install()
	{
		// 1. Create the database config file
		Installer::create_database_config(Installer::get_step_data(2, function() {
			Redirect::to('installer/step_2')->send();
			exit;
		}));

		// update config for this request instance
		$step2_data = Installer::get_step_data(2);
		Config::set('database.connections.'.$step2_data['driver'], array(
				'driver'   => $step2_data['driver'],
				'host'     => $step2_data['host'],
				'database' => $step2_data['database'],
				'username' => $step2_data['username'],
				'password' => $step2_data['password'],
				'charset'  => 'utf8',
				'prefix'   => '',
		));

		// 2. Create user
		$user = Installer::get_step_data(3, function() {
			Redirect::to('installer/step_3')->send();
			exit;
		});

		// override user with input format
		$user = array(
			'email'                 => $user['email'],
			'password'              => $user['password'],
			'password_confirmation' => $user['password_confirmation'],
			'groups'                => array('admin', 'users'),
			'metadata'              => array(
				'first_name' => $user['first_name'],
				'last_name'  => $user['last_name'],
			),
			'permissions' => array(
				Config::get('sentry::sentry.permissions.superuser') => 1,
			),
		);

		// 3. Create a random key
		Installer::generate_key();

		// 4. Install extensions
		Installer::install_extensions();

		try
		{
			$create_user = API::post('users', $user);
		}
		catch (APIClientException $e)
		{
			return Redirect::to('installer/step_3');
		}

		return Redirect::to('installer/step_4');
	}

	/**
	 * The completion step
	 *
	 * @return  View
	 */
	public function get_step_4()
	{
		Session::forget('installer');

		return View::make('installer::step_4')
		           ->with('license', Platform::license())
		           ->with('laravel_license', Platform::license('laravel'));
	}

	/**
	 * Returns a JSON encoded array of filesystem
	 * permissions.
	 *
	 * @return  Response
	 */
	public function get_permissions()
	{
		if ( ! Request::ajax())
		{
			return $this->get_index();
		}

		return new Response(json_encode(Installer::permissions()));
	}

	/**
	 * Confirm database - Step 1
	 *
	 * @return  Response
	 */
	public function post_confirm_db()
	{
		if ( ! Request::ajax())
		{
			return Event::fire('404');
		}

		try
		{
			Installer::check_database_connection(array(
				'driver'   => Input::get('driver'),
				'host'     => Input::get('host'),
				'database' => Input::get('database'),
				'username' => Input::get('username'),
				'password' => Input::get('password'),
			));
		}
		catch (Exception $e)
		{
			// Error 1146 is actually good, because it
			// means we connected fine, just couldn't
			// get the contents of the random table above.
			// For some reason this exception has a code of "0"
			// whereas all of the other exceptions match the
			// database errors. Life goes on.
			if ($e->getCode() !== 0)
			{
				return new Response(json_encode(array(
					'message' => $e->getMessage(),
				)), API::STATUS_BAD_REQUEST);
			}
		}

		return json_encode(array(
			'message' => 'Successfully connected to the database',
		));
	}

	public function post_confirm_user()
	{
		if ( ! Request::ajax())
		{
			return Event::fire('404');
		}

		$user = array(
			'email'                 => Input::get('email'),
			'password'              => Input::get('password'),
			'password_confirmation' => Input::get('password_confirmation'),
			'metadata'              => array(
				'first_name' => Input::get('first_name'),
				'last_name'  => Input::get('last_name'),
			),
		);

		$rules = array(
			'metadata.first_name'   => 'required',
			'metadata.last_name'    => 'required',
			'email'                 => 'required|email',
			'password_confirmation' => 'same:password',
		);

		$validation = Validator::make($user, $rules);

		if ($validation->fails())
		{
		    return json_encode(array(
		    	'error'   => true,
		    	'message' => $validation->errors->all(':message'),
		    ));
		}

		return json_encode(array(
			'error'   => false,
			'message' => array('Successfully validated user'),
		));
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return $this->get_index();
	}

}
