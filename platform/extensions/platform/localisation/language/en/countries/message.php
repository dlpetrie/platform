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
 * @version    1.1.0
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
    /*
     * -----------------------------------------
     * Per action messages.
     * -----------------------------------------
     */
    'create' => array(
        'success' => 'Country :country was successfully created.',
        'fail'    => 'An error occurred while creating the country !'
    ),

    'update' => array(
        'success'       => 'Country :country was successfully updated.',
        'fail'          => 'An error occurred while updating the country :country !',
        'disable_error' => 'You cannot disable a default country !',
        'default'       => 'Country :country is now the current default system country.'
    ),

    'delete' => array(
        'single' => array(
            'confirm'    => 'Are you sure you want to delete the country :country ?',
            'success'    => 'Country :country was successfully deleted.',
            'fail'       => 'An error occurred while deleting the country :country.',
            'being_used' => 'You cannot remove a country that is being used by the system.'
        ),
        'multi' => array(
            'confirm' => 'Are you sure you want to delete these countries ?',
            'success' => 'The countries selected were succesfully deleted.',
            'fail'    => 'An error occurred while trying to delete the selected countries'
        )
    ),


    /*
     * -----------------------------------------
     * Error messages.
     * -----------------------------------------
     */
    'error' => array(
        'not_found' => 'The country #:country was not found !'
    )
);
