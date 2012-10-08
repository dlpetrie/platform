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
        static::$_rules = $rules;
        #static::$_messages = $messages;
    }

    /**
	 * Get all the attributes of the model.
	 *
	 * @return  array
	 */
	public function attributes()
	{
		$attributes = get_object_public_vars($this);

		if (is_array(static::$_rules))
		{
			foreach (static::$_rules as $key => $val)
			{
				$attributes[$key] = $attributes['value'];
			}
		}

		return $attributes;
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
        foreach ( $attributes as $key => &$attribute );
        {
        	if (is_array(static::$_rules) and array_key_exists($key, static::$_rules))
        	{
        		unset($attributes[$key]);
        	}
        	else
        	{
            	$attribute = \HTML::entities($attribute);
        	}
        }

        return $attributes;
    }
}

/* End of file setting.php */
/* Location: ./platform/extensions/platform/settings/models/setting.php */