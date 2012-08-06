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

use Symfony\Component\HttpFoundation\LaravelRequest as RequestFoundation;
use Symfony\Component\HttpFoundation\Response as ResponseFoundation;

// Generic API Exception
class APIException extends Exception
{

	/**
	 * An array of errors for the
	 * Exception
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Sets / gets errors for an API
	 * Exception.
	 *
	 * @param   array  $errors
	 * @return  array  $errors
	 */
	public function errors(array $errors = array())
	{
		if ( ! empty($errors))
		{
			$this->errors = $errors;
		}

		return $this->errors;
	}

}

// Client exceptions
class APIClientException              extends APIException {}
class APIBadRequestException          extends APIClientException {}
class APIUnauthorizedException        extends APIClientException {}
class APIForbiddenException           extends APIClientException {}
class APINotFoundException            extends APIClientException {}
class APINotAllowedException          extends APIClientException {}

// Server exceptions
class APIServerException              extends APIException {}
class APIInternalServerErrorException extends APIServerException {}
class APIServiceUnavailableException  extends APIServerException {}

/**
 * This Api class acts as the base for
 * basically the entire application.
 *
 * @author      Daniel Petrie, Ben Corlett
 */
class API
{
	/*
	|--------------------------------------------------------------------------
	| API HTTP Statuses
	|--------------------------------------------------------------------------
	|
	| The Platform API uses normal HTTP status codes to help describe the
	| result of an action taken on the API.
	|
	| Codes are broken down into the following groups:
	|
	|  - 2xx: Success
	|  - 3xx: Redirecs
	|  - 4xx: Client Error
	|  - 4xx: Server Error
	|
	*/

	/**
	 * OK
	 *
	 * Everything is fine, I'm returning
	 * what you asked for.
	 *
	 * @constant
	 */
	const STATUS_OK  = 200;

	/**
	 * No Content
	 *
	 * I've created the resource you submitted,
	 * but I don't have anything to return.
	 *
	 * @constant
	 */
	const STATUS_NO_CONTENT = 204;

	/**
	 * Moved Permanently
	 *
	 * Anything maintaining links to this resource
	 * should update the links to the new location.
	 *
	 * @constant
	 */
	const STATUS_MOVED_PERMANENTLY = 301;

	/**
	 * Found
	 *
	 * Temporarily available at the new location.
	 *
	 * @constant
	 */
	const STATUS_FOUND = 302;

	/**
	 * Error
	 *
	 * General error condition, such as malformed 
	 * input data.
	 *
	 * @constant
	 */
	const STATUS_BAD_REQUEST = 400;

	/**
	 * Unauthorized
	 *
	 * You need to identify yourself before the
	 * request will be able to continue
	 *
	 * @constant
	 */
	const STATUS_UNAUTHORIZED = 401;

	/**
	 * Forbidden
	 *
	 * You have been identified but do not have
	 * permission to access this resource or run
	 * the requested action.
	 *
	 * This is usually an ACL issue.
	 *
	 * Note: Requests that require authentication
	 *       will return 404, instead of 403, in
	 *       some places. This is to prevent the
	 *       accidental leakage of private data
	 *       to unauthorized users.
	 *
	 * @constant
	 */
	const STATUS_FORBIDDEN = 403;

	/**
	 * Not Found
	 *
	 * The requested resource does not exist
	 *
	 * @constant
	 */
	const STATUS_NOT_FOUND = 404;

	/** 
	 * Not Allowed
	 *
	 * The requested HTTP verb is not
	 * allowed for this resource
	 *
	 * @constant
	 */
	const STATUS_NOT_ALLOWED = 405;

	/**
	 * Internal SErver Error
	 *
	 * General or Unkown error
	 *
	 * @constant
	 */
	const STATUS_INTERNAL_SERVER_ERROR = 500;

	/**
	 * Service Unavailable
	 *
	 * Usually indicates app server or database is unavailable 
	 *
	 * @constant
	 */
	const STATUS_SERVICE_UNAVAILABLE = 503;

	/**
	 * Flag for whether the current request
	 * is internal or external.
	 *
	 * @var bool
	 */
	protected static $internal_request = false;

	/**
	 * List of all available content types in
	 * the API.
	 *
	 * @var array
	 */
	public static $content_types = array(
		'json'       => 'application/json',
		'serialized' => 'application/vnd.php.serialized',
	);

	/**
	 * Used for making HEAD API calls
	 *
	 * @param   string  $uri
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function head($uri, array $data = array())
	{
		return static::call($uri, 'HEAD', $data);
	}

	/**
	 * Used for making GET API calls
	 *
	 * @param   string  $uri
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function get($uri, array $data = array())
	{
		return static::call($uri, 'GET', $data);
	}

	/**
	 * Used for making POST API calls
	 *
	 * @param   string  $uri
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function post($uri, array $data = array())
	{
		return static::call($uri, 'POST', $data);
	}

	/**
	 * Used for making PUT API calls
	 *
	 * @param   string  $uri
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function put($uri, array $data = array())
	{
		return static::call($uri, 'PUT', $data);
	}

	/**
	 * Used for making DELETE API calls
	 *
	 * @param   string  $uri
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function delete($uri, array $data = array())
	{
		return static::call($uri, 'DELETE', $data);
	}

	/**
	 * Makes intra-application API requests.
	 *
	 * @param   string  $uri
	 * @param   string  $method
	 * @param   array   $data
	 * @return  mixed
	 */
	public static function call($uri, $method = 'GET', $data = array())
	{
		// Convert
		$method = Str::upper($method);

		if ( ! $uri)
		{
			throw new Exception(Lang::line('api.invalid_uri', array(
				'uri' => $uri,
			)));
		}

		// Prefix the URI with api/
		$uri = API.'/'.ltrim($uri, '/');

		// Strip out any exteranl URLs. The
		// API is for interal requests only.
		$uri = parse_url($uri, PHP_URL_PATH);

		// Have a look for the requested format
		if ($format = pathinfo($uri, PATHINFO_EXTENSION))
		{
			// If we have a format, be sure to remove it
			// from the URI
			$uri = str_replace('.'.$format, null, $uri);

			// Validate the format is allowed, or fallback
			// to configuration item.
			$format = (array_key_exists($format, static::$content_types)) ? $format : Config::get('api.default_format', 'json');
		}
		else
		{
			$format = Config::get('api.default_format', 'json');
		}

		// Store the main request method and data
		$main_request = Request::$foundation;

		// Work out our query string and request data
		$query   = ($method === 'GET')  ? $data : array();
		$request = ($method === 'POST') ? $data : array();

		// Override the $_SERVER properties
		// for the internal request
		$server = array_merge($_SERVER, array(
			'REQUEST_METHOD' => $method,
			'REQUEST_URI'    => $uri,
		));

		// Create a new request object
		Request::$foundation = new RequestFoundation($query, $request, array(), $_COOKIE, $_FILES, $server);

		// Set the method
		Request::foundation()->setMethod($method);

		// Add the "Accept" header
		if ($format)
		{
			Request::foundation()->headers->add(array('accept' => static::$content_types[$format]));
		}

		// Set the internal request flag
		static::$internal_request = true;

		// Get the response from the request
		$response = Route::forward($method, $uri);

		// Set the internal request back to false
		static::$internal_request = false;

		// Reset the main request back into
		// Laravel. We've completed our HMVC
		// request.
		Request::$foundation = $main_request;

		// Reverse the action of the API_Controller
		// class so we get native data back for internal
		// API requests.
		switch ($format)
		{
			// Serialized PHP array
			case 'application/vnd.php.serialized':
				$response->content = unserialize($response->content);
			break;

			// JSON is also the default
			case 'application/json':
			default:
				$response->content = json_decode($response->content, 'assoc');
		}

		// If there's an area, prepare an
		// exception to be thrown
		if ($response->foundation->isClientError() or $response->foundation->isServerError())
		{
			// If we have a string as the content,
			// treat that as the message
			if (is_string($response->content))
			{
				$response->content = array(
					'message' => $response->content,
				);
			}

			// Check for required message field if we
			// were given an array
			elseif ( ! isset($response->content['message']))
			{
				// Default to the nornaml HTTP message
				// for the given status code
				$response->content['message'] = ResponseFoundation::$statusTexts[$response->status()];

				// throw new Exception(Lang::line('api.no_message_on_error', array(
				// 	'status' => $response->status(),
				// 	'method' => $method,
				// 	'uri'    => $uri,
				// )));
			}

			// Delegate the exception class
			if ($response->foundation->isClientError())
			{
				$class = 'APIClientException';

				switch ($response->status())
				{
					case 400:
						$class = 'APIBadRequestException';
						break;
					case 401:
						$class = 'APIUnauthorizedException';
						break;
					case 403:
						$class = 'APIForbiddenException';
						break;
					case 404:
						$class = 'APINotFoundException';
						break;
					case 405:
						$class = 'APINotAllowedException';
						break;
				}
			}
			else
			{
				$class = 'APIServerException';

				switch ($response->status())
				{
					case 500:
						$class = 'APIInternalServerErrorException';
						break;
					case 503:
						$class = 'APIServiceUnavailableException';
						break;
				}
			}

			$exception = new $class($response->content['message'], $response->status());

			if (isset($response->content['errors']) and is_array($response->content['errors']))
			{
				$exception->errors($response->content['errors']);
			}

			throw $exception;
		}

		// Return our content.
		return $response->content;
	}

	/**
	 * Returns if currently we're running
	 * an internal request (global).
	 *
	 * @return  bool
	 */
	public static function is_internal()
	{
		return (bool) static::$internal_request;
	}

}
