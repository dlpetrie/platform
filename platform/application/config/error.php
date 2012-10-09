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
 * Return the configuration.
 * --------------------------------------------------------------------------
 */
return array(
    /*
     * --------------------------------------------------------------------------
     * Ignored Error Levels
     * --------------------------------------------------------------------------
     * 
     * Here you simply specify the error levels that should be ignored by the
     * Laravel error handler. These levels will still be logged; however, no
     * information about about them will be displayed.
     * 
     */
    'ignore' => array(),


    /*
     * --------------------------------------------------------------------------
     * Error Detail
     * --------------------------------------------------------------------------
     * 
     * Detailed error messages contain information about the file in which an
     * error occurs, as well as a PHP stack trace containing the call stack.
     * You'll want them when you're trying to debug your application.
     * 
     * If your application is in production, you'll want to turn off the error
     * details for enhanced security and user experience since the exception
     * stack trace could contain sensitive information.
     * 
     */
    'detail' => true,


    /*
     * --------------------------------------------------------------------------
     * Error Logging
     * --------------------------------------------------------------------------
     * 
     * When error logging is enabled, the "logger" Closure defined below will
     * be called for every error in your application. You are free to log the
     * errors however you want. Enjoy the flexibility.
     * 
     */
    'log' => false,


    /*
     * --------------------------------------------------------------------------
     * Error Logger
     * --------------------------------------------------------------------------
     * 
     * Because of the various ways of managing error logging, you get complete
     * flexibility to manage error logging as you see fit. This function will
     * be called anytime an error occurs within your application and error
     * logging is enabled.
     * 
     * You may log the error message however you like; however, a simple log
     * solution has been setup for you which will log all error messages to
     * text files within the application storage directory.
     * 
     */
    'logger' => function($exception)
    {
        Log::exception($exception);
    }
);
