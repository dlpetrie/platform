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
 * Return the language lines.
 * --------------------------------------------------------------------------
 */
return array(
    // When an invalid URI was provided to the API.
    //
    'invalid_uri' => 'An invalid URI [:uri] was provided to the API',

    // Error for an invalid response format.
    //
    'invalid_instance' => 'Response must be an instance of :allowed, :instance given at [:method /:uri]',

    // Error given out when the response from an API call doesn't
    // meet the required criteria.
    //
    'no_message_on_error' => 'An API status of [:status] was returned however no message was returned for the user at [:method /:uri]',
);
