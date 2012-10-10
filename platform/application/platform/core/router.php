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
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/**
 * --------------------------------------------------------------------------
 * Platform > Core > Router Class
 * --------------------------------------------------------------------------
 * 
 * Platform > Core > Router Class
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Router extends Laravel\Routing\Router
{
    /**
     * The wildcard patterns supported by the router.
     *
     * @access    public
     * @var       array
     */
    public static $patterns = array(
        '(:num)' => '([0-9]+)',
        '(:any)' => '([a-zA-Z0-9\.\-_%=]+)',
        '(:all)' => '(.*)',
    );

    /**
     * The optional wildcard patterns supported by the router.
     *
     * @access    public
     * @var       array
     */
    public static $optional = array(
        '/(:num?)' => '(?:/([0-9]+)',
        '/(:any?)' => '(?:/([a-zA-Z0-9\.\-_%=]+)',
        '/(:all?)' => '(?:/(.*)',
    );

    /**
     * Requested Route Queue.
     *
     * @access    public
     * @var       array
     */
    public static $route_queue = array();


    /**
     * --------------------------------------------------------------------------
     * Function: route()
     * --------------------------------------------------------------------------
     *
     * Search the routes for the route matching a method and URI.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Route
     */
    public static function route($method, $uri)
    {
        Bundle::start($bundle = Bundle::handles($uri));

        $routes = (array) static::method($method);

        // Of course literal route matches are the quickest to find, so we will
        // check for those first. If the destination key exists in the routes
        // array we can just return that route now.
        if (array_key_exists($uri, $routes))
        {
            $action = $routes[$uri];

            $route = new Route($method, $uri, $action);

            static::add_to_queue($route);
            
            return new Route($method, $uri, $action);
        }

        // If we can't find a literal match we'll iterate through all of the
        // registered routes to find a matching route based on the route's
        // regular expressions and wildcards.
        if ( ! is_null($route = static::match($method, $uri)))
        {
            static::add_to_queue($route);

            return $route;
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: queue_next()
     * --------------------------------------------------------------------------
     *
     * Process the route queue.
     *
     * @access   public
     * @return   void
     */
    public static function queue_next()
    {
        // We process the queue by setting Request::$route to the last route in the queue.
        // If the queue is empty, it Request::$route remains the same
        if (count(static::$route_queue))
        {
            Request::$route = array_pop(static::$route_queue);
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: add_to_queue()
     * --------------------------------------------------------------------------
     *
     * Adds the previous route the the queue and sets the current Request route.
     *
     * @access   public
     * @param    object
     * @return   void
     */
    public static function add_to_queue($route)
    {
        // We only store the previous route into the queue, the active route is not stored
        if (Request::$route)
        {
            static::$route_queue[] = Request::$route;
            Request::$route = $route;
        }
    }
}