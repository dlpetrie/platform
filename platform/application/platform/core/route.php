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
 * Platform > Core > Route Class
 * --------------------------------------------------------------------------
 * 
 * Let's extend Laravel Route class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Route extends Laravel\Routing\Route
{
    /**
     * --------------------------------------------------------------------------
     * Function: call()
     * --------------------------------------------------------------------------
     *
     * Call a given route and return the route's response.
     *
     * @access   public
     * @return   Response
     */
    public function call()
    {
        // The route is responsible for running the global filters, and any
        // filters defined on the route itself, since all incoming requests
        // come through a route (either defined or ad-hoc).
        $response = Filter::run($this->filters('before'), array(), true);

        if (is_null($response))
        {
            $response = $this->response();
        }

        // We always return a Response instance from the route calls, so
        // we'll use the prepare method on the Response class to make
        // sure we have a valid Response instance.
        $response = Response::prepare($response);

        Filter::run($this->filters('after'), array($response));

        Router::queue_next();

        // Return the response.
        //
        return $response;
    }
}