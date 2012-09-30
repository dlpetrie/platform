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
 * Platform > Core > Redirect Class
 * --------------------------------------------------------------------------
 * 
 * Let's extend Laravel Redirect class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Redirect extends Laravel\Redirect
{
    /**
     * --------------------------------------------------------------------------
     * Function: back()
     * --------------------------------------------------------------------------
     *
     * Create a redirect response to the HTTP referrer.
     *
     * @access   public
     * @param    integer
     * @return   mixed
     */
    public static function back( $status = 302 )
    {
        return parent::to_secure( Request::referrer(), $status );
    }


    /**
     * --------------------------------------------------------------------------
     * Function: to_admin()
     * --------------------------------------------------------------------------
     *
     * Create a redirect response to an administration URL.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public static function to_admin( $url = null )
    {
        return parent::to_secure( ADMIN . '/' . $url );
    }
}

/* End of file redirect.php */
/* Location: ./platform/application/platform/core/redirect.php */