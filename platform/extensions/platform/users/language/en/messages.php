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

	'users' => array(

		'does_not_exist' => 'User does not exist.',
		'id_required'    => 'A User ID is required to update permissions.',

		'create' => array(
			'error'   => 'User was not created, please try again.',
			'success' => 'User created successfully.',
		),

		'update' => array(
			'error'   => 'User was not updated, please try again',
			'success' => 'User updated successfully.',
		),

		'delete' => array(
			'error'   => 'There was an issue deleting the user. Please try again.',
			'success' => 'The user was deleted successfully.',
		),

	),

	'groups' => array(

		'does_not_exist' => 'Group does not exist.',
		'id_required'    => 'A Group ID is required to update permissions.',

		'create' => array(
			'error'   => 'Group was not created, please try again.',
			'success' => 'Group created successfully.',
		),

		'update' => array(
			'error'   => 'Group was not updated, please try again',
			'success' => 'Group updated successfully.',
		),

		'delete' => array(
			'error'   => 'There was an issue deleting the user. Please try again.',
			'success' => 'The user was deleted successfully.',
		),

	),

	'auth' => array(
		'activate_account'         => ' - Activate Account',
		'activate_error'           => 'Could not activate user.',
		'error'                    => 'Check your emails!',
		'invalid_login'            => 'Invalid user name or password.',
		'password_confirm_success' => 'Your password reset has confirmed and updated successfully. You may now log in with your new password.',
		'password_confirm_error'   => 'There was an error confirming your password reset. Please try again.',
		'password_error'           => 'Unable to reset your password, please make sure both Email and Password are set and you are using a registered email address.',
		'password_success'         => 'Your password has been reset, please check your email to confirm.',
		'redirect'                 => 'Redirecting...',
		'success'                  => 'Succesfully logged in',
		'wait'                     => 'Please wait...',
	),

	'insufficient_permissions' => 'Insufficient Permissions',


);