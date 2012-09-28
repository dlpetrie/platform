<?php namespace Platform\Settings\Widgets;

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
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use API;
use APIClientException;
use Theme;


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
 * @version    1.1
 */
class Settings
{
    /**
     * The validation rules.
     *
     * @access   public
     * @param    array
     */
    public static $validation = array(
        'title' => 'required',
        'email' => 'required|email'
    );


    /**
     * --------------------------------------------------------------------------
     * Function: index()
     * --------------------------------------------------------------------------
     *
     * 
     *
     * @access   public
     * @param    array
     * @return   View
     */
    public function index( $settings = null )
    {
        return Theme::make('settings::widgets.form.general')->with('settings', $settings);
    }
}

/* End of file settings.php */
/* Location: ./platform/extensions/platform/settings/widgets/settings.php */