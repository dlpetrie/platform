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


/**
 * --------------------------------------------------------------------------
 * Admin Controller Class
 * --------------------------------------------------------------------------
 * 
 * The admin controller.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Admin_Controller extends Authorized_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: __construct()
     * --------------------------------------------------------------------------
     *
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        // Check if the user is logged in and has admin permissions.
        //
        $this->filter('before', 'admin_auth')->except($this->whitelist);

        // Call parent.
        //
        parent::__construct();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: before()
     * --------------------------------------------------------------------------
     *
     * This function is called before the action is executed.
     *
     * @access   public
     * @return   mixed
     */
    public function before()
    {
        // Make sure we have SSL url's.
        //
        if (Config::get('application.ssl') and ! Request::secure())
        {
            return Redirect::to_secure(URI::current())->send();
        }

        // Now make sure the user has extensions specific permissions.
        //
        if ( ! Sentry::user()->has_access())
        {
            return Redirect::to_admin('insufficient_permissions')->send();
        }

        // Set the active theme.
        //
        Theme::active('backend' . DS . Platform::get('themes.theme.backend'));

        // Set the fallback theme.
        //
        Theme::fallback('backend' . DS . 'default');
    }
}
