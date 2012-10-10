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

		'create' => array(
			'legend' => 'Create User',
			'summary' => 'Add a user to your application.',
			'first_name'       => 'First Name',
			'first_name_help' => 'Type your first name.',
			'last_name'        => 'Last Name',
			'last_name_help' => 'Type your last name.',
			'email'            => 'Email',
			'email_help' => 'Type your email address.',
			'password'         => 'Password',
			'password_help' => 'Type your password.',
			'password_confirm' => 'Confirm Password',
			'password_confirm_help' => 'Confirm your password.',
			'groups'           => 'Groups',
			'groups_help' => 'Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned.',
		),

		'edit' => array(
			'legend' => 'Create User',
			'summary' => 'Add a user to your application.',
			'first_name'       => 'First Name',
			'first_name_help' => 'Type your first name.',
			'last_name'        => 'Last Name',
			'last_name_help' => 'Type your last name.',
			'email'            => 'Email',
			'email_help' => 'Type your email address.',
			'password'         => 'Password',
			'password_help' => 'Type your password.',
			'password_confirm' => 'Confirm Password',
			'password_confirm_help' => 'Confirm your password.',
			'groups'           => 'Groups',
			'groups_help' => 'Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned.',
		),

	),

	'groups' => array(

		'create' => array(
			'name' => 'Name',
			'name_help' => 'What do you want to call this group?',
		),

		'edit' => array(
			'name' => 'Name',
			'name_help' => 'What do you want to call this group?',
		),
	),

	'auth' => array(

		'create' => array(
			'legend'                => 'Register',
			'summary'               => 'Join Platform',
			'first_name'            => 'First Name',
			'first_name_help'       => 'Type your first name.',
			'last_name'             => 'Last Name',
			'last_name_help'        => 'Type your last name.',
			'email'                 => 'Email',
			'email_help'            => 'Type your email address.',
			'email_confirm'         => 'Confirm Email',
			'email_confirm_help'    => 'Confirm your email address.',
			'password'              => 'Password',
			'password_help'         => 'Type your password.',
			'password_confirm'      => 'Confirm Password',
			'password_confirm_help' => 'Confirm your password.',
			'submit'                => 'Register',
			'cancel'                => 'Cancel',
			),

		'login' => array(
			'legend'           => 'Login',
			'summary'          => 'Welcome Back! Please sign in below.',
			'email'            => 'Email',
			'email_help'       => 'Type your email address.',
			'password'         => 'Password',
			'password_help'    => 'Type your password.',
			'submit'           => 'Login',
			'reset_password'   => 'Reset Password',
		),

		'reset' => array(
			'legend'           => 'Reset Password',
			'summary'          => 'An email will be sent with instructions',
			'email'            => 'Email',
			'email_help'       => 'Type your email address.',
			'password'         => 'New Password',
			'password_help'    => 'Type your new password.',
			'submit'           => 'Reset Password',
			'cancel'           => 'Cancel',
		),

	),

);
