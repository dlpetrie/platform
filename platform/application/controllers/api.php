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

use Symfony\Component\HttpFoundation\Response as ResponseFoundation;

class API_Controller extends Base_Controller
{

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return $this->no_route($method, $parameters);
	}

	/**
	 * Called in routes.php when no route leading with
	 * api/ can be resolved. We call this because __call()
	 * is slow.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function no_route($method = null, $parameters = null)
	{
		return new Response(ResponseFoundation::$statusTexts[404], 404);
	}

	/**
	 * @var  array  List of routes to whitelist from auth filter
	 */
	protected $whitelist = array();

	/**
	 * Note: This is a quick temporary fix
	 */
	// Override construct as it doesn't need CSRF checks for internal API calls
	public function __construct()
	{
		if ( ! API::is_internal() and ( ! Sentry::check() or ! Sentry::user()->has_access('is_admin')))
		{
			$this->filter('before', 'admin_auth')->except($this->whitelist);
		}
	}

	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		// See if the request is coming from the internal API
		if ( ! API::is_internal())
		{
			Event::fire('404');
			exit;
		}

		return parent::before();
	}

	/**
	 * Execute a controller method with the given parameters.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function execute($method, $parameters = array())
	{
		// Wrap everything in a try, as to not expose sensitive
		// information
		try
		{
			$filters = $this->filters('before', $method);

			// Again, as was the case with route closures, if the controller "before"
			// filters return a response, it will be considered the response to the
			// request and the controller method will not be used.
			$response = Filter::run($filters, array(), true);

			if (is_null($response))
			{
				$this->before();

				$response = $this->response($method, $parameters);
			}

			// Validate our response
			if ( ! $response instanceof Response)
			{
				// throw new Exception(Lang::line('api.invalid_instance', array(
				// 	'allowed'  => get_class(with(new Response(''))),
				// 	'instance' => gettype($response),
				// )));

				Log::api(Lang::line('api.invalid_instance', array(
					'allowed'  => get_class(with(new Response(''))),
					'instance' => gettype($response),
				)));

				// Convert it now
				$response = new Response($response);
			}

			// The "after" function on the controller is simply a convenient hook
			// so the developer can work on the response before it's returned to
			// the browser. This is useful for templating, etc.
			$this->after($response);
		}
		catch (Exception $e)
		{
			if (API::is_internal())
			{
				throw $e;
			}

			$response = new Response(ResponseFoundation::$statusTexts[500], 500);
		}

		// If there is no format available, use
		// the default format.
		if ( ! $format = Request::foundation()->headers->get('accept'))
		{
			$format = API::$content_types[Config::get('api.default_format', 'json')];
		}

		// Transform our response content into the required
		// format
		switch ($format)
		{
			// Serialized PHP array
			case 'application/vnd.php.serialized':
				$response->content = serialize($response->content);
			break;

			// JSON is also the default
			case 'application/json':
			default:
				$response->content = json_encode($response->content);
			break;
		}

		Filter::run($this->filters('after', $method), array($response));

		return $response;
	}

}
