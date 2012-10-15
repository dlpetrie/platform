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

namespace Platform\Settings\Widgets;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use API,
    APIClientException,
    Theme\Theme;


/**
 * --------------------------------------------------------------------------
 * Settings > Widget Class
 * --------------------------------------------------------------------------
 *
 * The settings widgets class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Settings
{
    /**
     * The validation rules.
     *
     * @access   public
     * @var      array
     */
    public static $validation = array(
        'title'   => 'required|min:3',
        'tagline' => 'required|min:3',
        'email'   => 'required|email'
    );


    /**
     * --------------------------------------------------------------------------
     * Function: index()
     * --------------------------------------------------------------------------
     *
     * Shows the general settings form.
     *
     * @access   public
     * @param    array
     * @return   View
     */
    public function index($settings = null)
    {
    	$filesystem_options = array(
			'error'   => 'Error',
			'info'    => 'Info',
			'sucess'  => 'Success',
			'warning' => 'Warning',
			'off'     => 'Off',
    	);

        // Show the form.
        //
        return Theme::make('settings::widgets.form.settings')
        	->with('settings', $settings)
        	->with('filesystem_options', $filesystem_options);
    }
}
