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
 * @version    1.0.1
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * Admin routing.
 * --------------------------------------------------------------------------
 *
 * We can use this for the administration extensions.
 *
 */
Route::any(ADMIN . '/(:any?)/(:any?)/(:any?)(/.*)?', function($bundle = 'dashboard', $controller = null, $action = null, $params = null)
{
    // Check if the extension exists.
    //
    if ( ! Bundle::exists($bundle))
    {
        return Response::error('404');
    }

    // Check if the controller exists.
    //
    if (Controller::resolve($bundle, 'admin.' . $controller))
    {
        $controller = $bundle . '::admin.' . $controller . '@' . (($action) ?: 'index');
        $params     = explode('/', substr($params, 1));
    }

    // If it doesn't, default to the bundle name as a controller.
    //
    else
    {
        $controller = $bundle . '::admin.' . $bundle . '@' . (($controller) ?: 'index');
        $params     = explode('/', $action.$params);
    }

    // Execute the controller.
    //
    return Controller::call($controller, $params);
});


/*
 * --------------------------------------------------------------------------
 * API routing.
 * --------------------------------------------------------------------------
 *
 * Route /api/extension/:id
 *
 *  <code>
 *      /api/users/1 => users::api.users@index(1)
 *  </code>
 */
Route::any(API . '/(:any)/(:num)', function($bundle = DEFAULT_BUNDLE, $id = null, $params = null)
{
    return Controller::call($bundle . '::api.' . $bundle . '@index', array($id));
});


/*
 * --------------------------------------------------------------------------
 * API routing.
 * --------------------------------------------------------------------------
 *
 * Route /api/extension/controller/:id
 *
 *  <code>
 *      /api/users/groups/1 => users::api.users.groups@index(1)
 *  </code>
 */
Route::any(API . '/(:any)/(:any)/(:num)', function($bundle = DEFAULT_BUNDLE, $controller = null, $id = null, $params = null)
{
    return Controller::call($bundle . '::api.' . $controller . '@index', array($id));
});


/*
 * --------------------------------------------------------------------------
 * API routing.
 * --------------------------------------------------------------------------
 *
 * Re-route api controllers.
 *
 */
Route::any(array(API . '/(:any?)/(:any?)/(:any?)(/.*)?', API . '/(:any?)/(:any?)(/.*)?', API . '/(:any?)(/.*)?'), function($bundle = 'dashboard', $controller = null, $action = null, $params = null)
{
    // Check if the extension exists.
    //
    if ( ! Bundle::exists($bundle))
    {
        $bundle = DEFAULT_BUNDLE;
    }

    // Check if the controller exists.
    //
    if (Controller::resolve($bundle, $_controller = 'api.' . $controller))
    {
        $controller = $bundle . '::' . $_controller . '@' . (($action) ?: 'index');
        $params     = explode('/', substr($params, 1));
    }

    // If it doesn't, default to the bundle name as a controller.
    //
    elseif (Controller::resolve($bundle, $_controller = 'api.' . $bundle))
    {
        $controller = $bundle . '::' . $_controller . '@' . (($controller) ?: 'index');
        $params     = explode('/', $action.$params);
    }

    // Fallback to API controller.
    //
    else
    {
        $controller = 'api@no_route';
        $params     = array();
    }

    // Execute the controller.
    //
    return Controller::call($controller, $params);
});


/*
 * --------------------------------------------------------------------------
 * Now detect controllers.
 * --------------------------------------------------------------------------
 */
Route::controller(Controller::detect());


/*
 *--------------------------------------------------------------------------
 * Application 404 & 500 Error Handlers
 *--------------------------------------------------------------------------
 *
 * To centralize and simplify 404 handling, Laravel uses an awesome event
 * system to retrieve the response. Feel free to modify this function to
 * your tastes and the needs of your application.
 *
 * Similarly, we use an event to handle the display of 500 level errors
 * within the application. These errors are fired when there is an
 * uncaught exception thrown in the application.
 *
 */
Event::listen('404', function(){ return Response::error('404'); });
Event::listen('500', function(){ return Response::error('500'); });


/*
 * --------------------------------------------------------------------------
 *  Route Filters
 * --------------------------------------------------------------------------
 * 
 *  Filters provide a convenient method for attaching functionality to your
 *  routes. The built-in before and after filters are called before and
 *  after every request to your application, and you may even create
 *  other filters that can be attached to individual routes.
 * 
 *  Let's walk through an example...
 * 
 *  First, define a filter:
 * 
 *         Route::filter('filter', function()
 *         {
 *             return 'Filtered!';
 *         });
 * 
 *  Next, attach the filter to a route:
 * 
 *         Router::register('GET /', array('before' => 'filter', function()
 *         {
 *             return 'Hello World!';
 *         }));
 * 
 */
Route::filter('before', function(){});
Route::filter('after', function($response){});


/*
 *--------------------------------------------------------------------------
 * CSRF ( Cross-site request forgery ) Protection.
 *--------------------------------------------------------------------------
 */
Route::filter('csrf', function()
{
    // Bad request ?
    //
    if (Request::forged())
    {
        return Response::error('500');
    }

    // Remove the token from the input now.
    //
    Request::foundation()->request->remove(Session::csrf_token);
});


/*
 *--------------------------------------------------------------------------
 * Authentication filter.
 *--------------------------------------------------------------------------
 */
Route::filter('auth', function()
{
    // Check if the user is logged in.
    //
    if ( ! Sentry::check())
    {
        // Store the current uri in the session.
        //
        Session::put('login_redirect', URI::current());

        // Redirect to the login page.
        //
        return Redirect::to('login');
    }
});


/*
 *--------------------------------------------------------------------------
 * Admin authentication filter.
 *--------------------------------------------------------------------------
 *
 * Usefull to check if a user is logged in and has access to the admin page.
 *
 */
Route::filter('admin_auth', function()
{
    // Check if the user is logged in and has acces to admin.
    //
    if ( ! Sentry::check() or ! Sentry::user()->has_access('is_admin'))
    {
        // Store the current uri in the session.
        //
        Session::flash('login_redirect', URI::current());

        // Redirect to the login page.
        //
        return Redirect::to('login');
    }
});
