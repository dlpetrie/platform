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
 * PHP Display Errors Configuration
 * --------------------------------------------------------------------------
 *
 * Since Laravel intercepts and displays all errors with a detailed stack
 * trace, we can turn off the display_errors ini directive. However, you
 * may want to enable this option if you ever run into a dreaded white
 * screen of death, as it can provide some clues.
 *
 */
ini_set('display_errors', 'On');


/*
 * --------------------------------------------------------------------------
 * Laravel Configuration Loader
 * --------------------------------------------------------------------------
 *
 * The Laravel configuration loader is responsible for returning an array
 * of configuration options for a given bundle and file. By default, we
 * use the files provided with Laravel; however, you are free to use
 * your own storage mechanism for configuration arrays.
 *
 */
Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
    return Laravel\Config::file($bundle, $file);
});


/*
 * --------------------------------------------------------------------------
 * Register Class Aliases
 * --------------------------------------------------------------------------
 *
 * Aliases allow you to use classes without always specifying their fully
 * namespaced path. This is convenient for working with any library that
 * makes a heavy use of namespace for class organization. Here we will
 * simply register the configured class aliases.
 *
 */
$aliases = Laravel\Config::get('application.aliases');
Laravel\Autoloader::$aliases = $aliases;


/*
 * --------------------------------------------------------------------------
 * Auto-Loader Mappings
 * --------------------------------------------------------------------------
 *
 * Registering a mapping couldn't be easier. Just pass an array of class
 * to path maps into the "map" function of Autoloader. Then, when you
 * want to use that class, just use it. It's simple!
 *
 */
Autoloader::map(array(
    'API_Controller'        => __DIR__ . DS . 'controllers' . DS . 'api' . EXT,
    'Admin_Controller'      => __DIR__ . DS . 'controllers' . DS . 'admin' . EXT,
    'Authorized_Controller' => __DIR__ . DS . 'controllers' . DS . 'authorized' . EXT,
    'Base_Controller'       => __DIR__ . DS . 'controllers' . DS . 'base' . EXT,
    'Public_Controller'     => __DIR__ . DS . 'controllers' . DS . 'public' . EXT
));


/*
 * --------------------------------------------------------------------------
 * Auto-Loader Directories
 * --------------------------------------------------------------------------
 *
 * The Laravel auto-loader can search directories for files using the PSR-0
 * naming convention. This convention basically organizes classes by using
 * the class namespace to indicate the directory structure.
 *
 */
Autoloader::directories(array(
    __DIR__ . DS . 'models',
    __DIR__ . DS . 'libraries',
    __DIR__ . DS . 'platform' . DS . 'core',
    __DIR__ . DS . 'platform'
));


/*
 * --------------------------------------------------------------------------
 * Auto-Loader Namespaces
 * --------------------------------------------------------------------------
 */
Autoloader::namespaces(array(
    'Platform\\Application\\Widgets' => __DIR__ . DS . 'widgets'
));


/*
 * --------------------------------------------------------------------------
 * Laravel View Loader
 * --------------------------------------------------------------------------
 *
 * The Laravel view loader is responsible for returning the full file path
 * for the given bundle and view. Of course, a default implementation is
 * provided to load views according to typical Laravel conventions but
 * you may change this to customize how your views are organized.
 *
 */
Event::listen(View::loader, function($bundle, $view)
{
    return Theme::file($bundle, $view);
});


/*
 * --------------------------------------------------------------------------
 * Laravel Language Loader
 * --------------------------------------------------------------------------
 *
 * The Laravel language loader is responsible for returning the array of
 * language lines for a given bundle, language, and "file". A default
 * implementation has been provided which uses the default language
 * directories included with Laravel.
 *
 */
Event::listen(Lang::loader, function($bundle, $language, $file)
{
    return Lang::file($bundle, $language, $file);
});


/*
 * --------------------------------------------------------------------------
 * Attach The Laravel Profiler
 * --------------------------------------------------------------------------
 *
 * If the profiler is enabled, we will attach it to the Laravel events
 * for both queries and logs. This allows the profiler to intercept
 * any of the queries or logs performed by the application.
 *
 */
if (Config::get('application.profiler'))
{
    Profiler::attach();
}


/*
 * --------------------------------------------------------------------------
 * Enable The Blade View Engine
 * --------------------------------------------------------------------------
 *
 * The Blade view engine provides a clean, beautiful templating language
 * for your application, including syntax for echoing data and all of
 * the typical PHP control structures. We'll simply enable it here.
 *
 */
Blade::sharpen();


/*
 * --------------------------------------------------------------------------
 * Set The Default Timezone
 * --------------------------------------------------------------------------
 *
 * We need to set the default timezone for the application. This controls
 * the timezone that will be used by any of the date methods and classes
 * utilized by Laravel or your application. The timezone may be set in
 * your application configuration file.
 *
 */
date_default_timezone_set(Config::get('application.timezone'));


/*
 * --------------------------------------------------------------------------
 * Start / Load The User Session
 * --------------------------------------------------------------------------
 *
 * Sessions allow the web, which is stateless, to simulate state. In other
 * words, sessions allow you to store information about the current user
 * and state of your application. Here we'll just fire up the session
 * if a session driver has been configured.
 *
 */
if ( ! Request::cli() and Config::get('session.driver') !== '')
{
    Session::load();
}


/*
 * --------------------------------------------------------------------------
 * Crud
 * --------------------------------------------------------------------------
 *
 * Crud is our base model for everything in Platform. We'll kick it off
 * here so it's loaded for when we start using it in
 * Platform::is_installed() and Platform::start().
 *
 */
Bundle::start('crud');


/**
 * --------------------------------------------------------------------------
 * Initialize Platform
 * --------------------------------------------------------------------------
 *
 * Let's start Platform, this will determine if Platform is installed or
 * needs to be installed.
 *
 */
Platform::start();
