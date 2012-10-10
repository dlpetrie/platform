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

namespace Platform\Application\Widgets;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Theme;


/**
 * --------------------------------------------------------------------------
 * Messages widget
 * --------------------------------------------------------------------------
 * 
 * This widget purpose is to show messages on the UI.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Messages
{
    /**
     * --------------------------------------------------------------------------
     * Function: all()
     * --------------------------------------------------------------------------
     *
     * Shows all the messages.
     *
     * @access   public
     * @return   View
     */
    public function all()
    {
        return Theme::make('widgets.messages.all');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: success()
     * --------------------------------------------------------------------------
     *
     * Shows all the success messages.
     *
     * @access   public
     * @return   View
     */
    public function success()
    {
        return Theme::make('widgets.messages.success');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: errors()
     * --------------------------------------------------------------------------
     *
     * Shows all the error messages.
     *
     * @access   public
     * @return   View
     */
    public function errors()
    {
        return Theme::make('widgets.messages.errors');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: warning()
     * --------------------------------------------------------------------------
     *
     * Shows all the warning messages.
     *
     * @access   public
     * @return   View
     */
    public function warning()
    {
        return Theme::make('widgets.messages.warning');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: info()
     * --------------------------------------------------------------------------
     *
     * Shows all the information messages.
     *
     * @access   public
     * @return   View
     */
    public function info()
    {
        return Theme::make('widgets.messages.info');
    }
}
