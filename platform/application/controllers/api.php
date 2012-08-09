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
	 * Whitelisted auth routes.
	 *
	 * @var  array
	 */
	protected $whitelist = array();

	/**
	 * Flag for whether we can hide the
	 * API controller. If left null or not
	 * set, will be automatically determined
	 * based off user access and whether the
	 * request is internal or external.
	 *
	 * @var  bool
	 */
	protected $restrict_method;

	/**
	 * Called when the class object is
	 * initialized
	 */
	public function __construct()
	{

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
		return new Response(array(
			'message' => ResponseFoundation::$statusTexts[404],
		), 404);
	}

	/**
	 * Returns whether the current method
	 * should be restricted from showing. This
	 * is usually because it's a protected API
	 * method and the user isn't authenticated.
	 *
	 * @todo Split this into three methods, one
	 *       for not logged in, one for incorrect
	 *       credentials and one for ACL denied.
	 *
	 * @return  bool
	 */
	protected function restrict_method($method = null)
	{
		if ($this->restrict_method === null)
		{
			$this->restrict_method = ( ! API::is_internal() and ( ! Sentry::check() or ! Sentry::user()->has_access($method)));
		}

		return $this->restrict_method;
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
			// Firstly, let's check if we're running an internal request or not.
			// External calls are okay as long as we have authentication and
			// ACL access.
			if ($this->restrict_method() === true and ( ! in_array($method, $this->whitelist)))
			{
				// We'll throw a 404
				$response = $this->no_route();
			}
			else
			{
				$filters = $this->filters('before', $method);

				// Again, as was the case with route closures, if the controller "before"
				// filters return a response, it will be considered the response to the
				// request and the controller method will not be used.
				$response = Filter::run($filters, array(), true);

				// No filter passed through?
				if (is_null($response))
				{
					$this->before();

					$response = $this->response($method, $parameters);
				}
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
					'method'   => Request::method(),
					'uri'      => Request::server('REQUEST_URI'),
				)));

				// Convert it now
				if (is_null($response) or $response == '')
				{
					$response = new Response(null, API::STATUS_NO_CONTENT);
				}
				else
				{
					$response = new Response($response);
				}
			}

			// The "after" function on the controller is simply a convenient hook
			// so the developer can work on the response before it's returned to
			// the browser. This is useful for templating, etc.
			if ($this->restrict_method() === false)
			{
				$this->after($response);
			}
		}
		catch (Exception $e)
		{
			if (API::is_internal())
			{
				throw $e;
			}

			$response = new Response(array(
				'message' => ResponseFoundation::$statusTexts[500],
			), 500);
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
