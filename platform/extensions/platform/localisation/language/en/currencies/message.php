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
        'success' => 'Currency :currency was successfully created.',
        'fail'    => 'An error occurred while creating the currency !'
    ),

    'update' => array(
        'success'       => 'Currency :currency was successfully updated.',
        'fail'          => 'An error occurred while updating the currency :currency !',
        'disable_error' => 'You cannot disable a default currency !',
        'default'       => 'Currency :currency is now the current default system currency.'
    ),

    'delete' => array(
        'single' => array(
            'confirm'    => 'Are you sure you want to delete the currency :currency ?',
            'success'    => 'Currency :currency was successfully deleted.',
            'fail'       => 'An error occurred while deleting the currency :currency.',
            'being_used' => 'You cannot remove a currency that is being used by the system.'
        ),
        'multi' => array(
            'confirm' => 'Are you sure you want to delete these currencies ?',
            'success' => 'The currencies selected were succesfully deleted.',
            'fail'    => 'An error occurred while trying to delete the selected currencies'
        )
    ),


    /*
     * -----------------------------------------
     * Error messages.
     * -----------------------------------------
     */
    'error' => array(
        'not_found' => 'The currency #:currency was not found !'
    )
);
