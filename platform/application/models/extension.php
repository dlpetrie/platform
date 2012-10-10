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
 * Extension Class
 * --------------------------------------------------------------------------
 * 
 * Extension model class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Extension extends Crud
{
    /**
     * --------------------------------------------------------------------------
     * Function: find()
     * --------------------------------------------------------------------------
     *
     * Finds an extension by either it's primary key or by a condition that
     * modifies the query object.
     *
     * @access   public
     * @param    mixed
     * @param    array
     * @param    array
     * @return   object
     */
    public static function find($condition = 'first', $columns = array('*'), $events = array('before', 'after'))
    {
        // Find the extension by slug.
        //
        if (is_string($condition) and ! is_numeric($condition) and ! in_array($condition, array('first', 'last')))
        {
            return parent::find(function($query) use ($condition)
            {
                return $query->where('slug', '=', $condition);
            }, $columns, $events);
        }

        // Call parent.
        //
        return parent::find($condition, $columns, $events);
    }
}
