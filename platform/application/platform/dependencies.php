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
 * Dependencies Class
 * --------------------------------------------------------------------------
 * 
 * Sort the extensions dependencies.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Dependencies
{
    private static $items        = array();
    private static $dependencies = array();


    /**
     * --------------------------------------------------------------------------
     * Function: sort()
     * --------------------------------------------------------------------------
     *
     * Prepares the items dependencies to be sorted.
     *
     * @access   public
     * @param    array
     * @return   array
     */
    public static function sort( $items = null )
    {
        // Make sure we have items.
        //
        if ( is_null( $items ) or ! is_array( $items ) or empty( $items ) ):
            return false;
        endif;

        // Spin through the items.
        //
        foreach ( $items as $item => $data ):
            // Add this item to the aray.
            //
            static::$items[] = $item;

            // Get this item dependencies.
            //
            $dependencies = array_filter( ( isset( $data['dependencies'] ) && is_array( $data['dependencies'] ) && ! empty( $data['dependencies'] ) ? $data['dependencies'] : array() ) );

            // Store this item dependencies.
            //
            static::$dependencies[ $item ] = $dependencies;
        endforeach;

        // Return the dependencies in the proper order.
        //
        return static::_sort();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: _sort()
     * --------------------------------------------------------------------------
     *
     * This sorts the extensions dependencies.
     *
     * @access   protected
     * @return   array
     */
    protected static function _sort()
    {
        // Initiate an empty array, so we can save the sorted dependencies.
        //
        $sorted = array();

        // Initiate a flag.
        //
        $changed = true;

        // Make some checks and loops =)
        //
        while ( count( $sorted ) < count( static::$items ) && $changed === true ):
            // Mark the flag as false.
            //
            $changed = false;

            // Spin through the dependencies.
            //
            foreach ( static::$dependencies as $item => $dependencies ):
                // Check if this item has all the dependencies.
                //
                if ( static::validate( $item, $sorted ) ):
                    $sorted[] = $item;
                    unset( static::$dependencies[ $item ]);
                    $changed = true;
                endif;
            endforeach;
        endwhile;

        // Return the sorted dependencies.
        //
        return $sorted;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: validate()
     * --------------------------------------------------------------------------
     *
     * This validates if an item is valid.
     *
     * @access   private
     * @param    string
     * @param    array
     * @return   boolean
     */
    private static function validate( $item = null, $sorted = array() )
    {
        // Spin through this item dependencies.
        //
        foreach ( static::$dependencies[ $item ] as $dependency ):
            // Check if this dependency exists.
            //
            if ( ! in_array( $dependency, $sorted ) ):
                // Item is invalid.
                //
                return false;
            endif;
        endforeach;

        // Item is valid.
        //
        return true;
    }
}

/* End of file dependencies.php */
/* Location: ./platform/application/platform/dependencies.php */