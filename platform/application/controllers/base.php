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


/**
 * --------------------------------------------------------------------------
 * Base Controller Class
 * --------------------------------------------------------------------------
 * 
 * Base controller.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Base_Controller extends Controller
{
    /**
     * Flag for whether the controller is RESTful.
     *
     * @access    public
     * @var       boolean
     */
    public $restful = true;


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
        // CSRF Protection.
        //
        $this->filter('before', 'csrf')->on('post');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: __call()
     * --------------------------------------------------------------------------
     *
     * Catch-all method for requests that can't be matched.
     *
     * @access   public
     * @param    string
     * @param    array
     * @return   Response
     */
    public function __call($method, $parameters)
    {
        return Event::first('404');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: before()
     * --------------------------------------------------------------------------
     *
     * This function is called before the action is executed.
     *
     * @access   public
     * @return   void
     */
    public function before()
    {
        // Wait for the event.
        //
        Event::fire('platform.controller.before');

        // Call parent.
        //
        return parent::before();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: after()
     * --------------------------------------------------------------------------
     *
     * This function is called after the action is executed.
     *
     * @access   public
     * @param    mixed
     * @return   void
     */
    public function after($response)
    {
        // Wait for the event.
        //
        Event::fire('platform.controller.after', array($response));

        // Call parent.
        //
        return parent::after($response);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: active_menu()
     * --------------------------------------------------------------------------
     *
     * Sets the active menu slug.
     *
     * @access   public
     * @param    string
     * @return   void
     */
    public function active_menu($slug)
    {
        API::post('menus/active', array('slug' => $slug));
    }
}
