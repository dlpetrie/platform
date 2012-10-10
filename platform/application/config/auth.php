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
 * Return the configuration.
 * --------------------------------------------------------------------------
 */
return array(
    /*
     * --------------------------------------------------------------------------
     * Default Authentication Driver
     * --------------------------------------------------------------------------
     * 
     * Laravel uses a flexible driver-based system to handle authentication.
     * You are free to register your own drivers using the Auth::extend
     * method. Of course, a few great drivers are provided out of
     * box to handle basic authentication simply and easily.
     * 
     * Drivers: 'fluent', 'eloquent'.
     * 
     */
    'driver' => 'eloquent',


    /*
     * --------------------------------------------------------------------------
     * Authentication Username
     * --------------------------------------------------------------------------
     * 
     * Here you may specify the database column that should be considered the
     * "username" for your users. Typically, this will either be "username"
     * or "email". Of course, you're free to change the value to anything.
     * 
     */
    'username' => 'email',


    /*
     * --------------------------------------------------------------------------
     * Authentication Model
     * --------------------------------------------------------------------------
     * 
     * When using the "eloquent" authentication driver, you may specify the
     * model that should be considered the "User" model. This model will
     * be used to authenticate and load the users of your application.
     * 
     */
    'model' => 'User',


    /*
     * --------------------------------------------------------------------------
     * Authentication Table
     * --------------------------------------------------------------------------
     * 
     * When using the "fluent" authentication driver, the database table used
     * to load users may be specified here. This table will be used in by
     * the fluent query builder to authenticate and load your users.
     * 
     */
    'table' => 'users'
);
