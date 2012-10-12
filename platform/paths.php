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


/*
 * --------------------------------------------------------------------------
 * Application Environments
 * --------------------------------------------------------------------------
 *
 * Laravel takes a dead simple approach to environments, and we
 * think you'll love it. Just specify which URLs belongs to a
 * given environment, and when you access your application
 * from a URL matching that pattern, we'll be sure to
 * merge in that environment's configuration files.
 *
 */
$environments = array(
    'local' => array('http://local*', 'http://localhost*', '*.dev')
);


/*
 * --------------------------------------------------------------------------
 * Set the Admin and API routes.
 * --------------------------------------------------------------------------
 *
 * We set these routes here so module.php files have access to these constants.
 *
 * If we set them in routes, modules do not have access to them since they
 * would load before the define is set.
 *
 */
define('ADMIN', 'admin');
define('API', 'api');


/*
 * --------------------------------------------------------------------------
 * The path to the application directory.
 * --------------------------------------------------------------------------
 */
$paths['app'] = 'application';


/*
 * --------------------------------------------------------------------------
 * The path to the Laravel directory.
 * --------------------------------------------------------------------------
 */
$paths['sys'] = 'laravel';


/*
 * --------------------------------------------------------------------------
 * The path to the bundles directory.
 * --------------------------------------------------------------------------
 */
$paths['bundle'] = 'bundles';


/*
 * --------------------------------------------------------------------------
 * The path to the storage directory.
 * --------------------------------------------------------------------------
 */
$paths['storage'] = 'storage';


/*
 * --------------------------------------------------------------------------
 * The path to the public directory.
 * --------------------------------------------------------------------------
 */
$paths['public'] = '../public';


/*
 * --------------------------------------------------------------------------
 * The path to the Platform installer.
 * --------------------------------------------------------------------------
 */
$paths['installer'] = 'installer';


/*
 * --------------------------------------------------------------------------
 * The path to the extensions directory.
 * --------------------------------------------------------------------------
 */
$paths['extensions'] = 'extensions';


/*
 * --------------------------------------------------------------------------
 * The path to the licenses directory.
 * --------------------------------------------------------------------------
 */
$paths['licenses'] = 'licenses';


// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
// END OF USER CONFIGURATION. HERE BE DRAGONS!
// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*


/*
 * --------------------------------------------------------------------------
 * Change to the current working directory.
 * --------------------------------------------------------------------------
 */
chdir(__DIR__);


/*
 * --------------------------------------------------------------------------
 * Define the directory separator for the environment.
 * --------------------------------------------------------------------------
 */
if ( ! defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}


/*
 * --------------------------------------------------------------------------
 * Define the path to the base directory.
 * --------------------------------------------------------------------------
 */
$GLOBALS['laravel_paths']['base'] = __DIR__ . DS;


/*
 * --------------------------------------------------------------------------
 * Define each path as a constant if it hasn't been defined yet.
 * --------------------------------------------------------------------------
 */
foreach ($paths as $name => $path)
{
    // Check if the constant is not defined.
    //
    if ( ! isset($GLOBALS['laravel_paths'][ $name ]))
    {
        // Define the constant.
        //
        $GLOBALS['laravel_paths'][ $name ] = realpath($path) . DS;
    }
}


/**
 * --------------------------------------------------------------------------
 * Function: path()
 * --------------------------------------------------------------------------
 *
 * A global path helper function.
 *
 *  <code>
 *      $storage = path('storage');
 *  </code>
 *
 * @access   public
 * @param    string
 * @return   string
 */
function path($path)
{
    return $GLOBALS['laravel_paths'][ $path ];
}


/**
 * --------------------------------------------------------------------------
 * Function: set_path()
 * --------------------------------------------------------------------------
 *
 * A global path setter function.
 *
 * @access   public
 * @param    string
 * @param    string
 * @return   void
 */
function set_path($path, $value)
{
    $GLOBALS['laravel_paths'][ $path ] = $value;
}
