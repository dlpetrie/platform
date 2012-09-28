<?php namespace Platform\Settings\Model;

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
use Crud;


/**
 * --------------------------------------------------------------------------
 * Settings > Setting Model
 * --------------------------------------------------------------------------
 * 
 * The settings model class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Setting extends Crud
{
    /**
     * --------------------------------------------------------------------------
     * Function: set_validation()
     * --------------------------------------------------------------------------
     *
     * Set validation rules and labels.
     *
     * @access   public
     * @param    array
     * @param    array
     * @return   void
     */
    public function set_validation( $rules = array(), $messages = array() )
    {
        static::$_rules  = $rules;
        #static::$_messages = $messages;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: prep_attributes()
     * --------------------------------------------------------------------------
     *
     * Called right after validation before inserting/updating to the database.
     *
     * @access   public
     * @param    array
     * @return   array
     */
    protected function prep_attributes( $attributes )
    {
        foreach ( $attributes as &$attribute );
        {
            $attribute = \HTML::entities($attribute);
        }

        return $attributes;
    }
}

/* End of file setting.php */
/* Location: ./platform/extensions/platform/settings/models/setting.php */