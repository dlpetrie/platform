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


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Platform\Menus\Menu;


/**
 * --------------------------------------------------------------------------
 * Add Class to Menus Class
 * --------------------------------------------------------------------------
 * 
 * Adds a class to menu items.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 */
class Extensions_Add_Class_To_Menus
{
    /**
     * --------------------------------------------------------------------------
     * Function: up()
     * --------------------------------------------------------------------------
     *
     * Make changes to the database.
     *
     * @access   public
     * @return   void
     */
    public function up()
    {
        /*
         * --------------------------------------------------------------------------
         * # 1) Update the menu items.
         * --------------------------------------------------------------------------
         */
        // Get the admin menu.
        //
        $admin      = Menu::admin_menu();
        $admin_tree = $admin->{Menu::nesty_col('tree')};

        // Update the extensions class.
        //
        $extensions = Menu::find(function($query) use ($admin_tree)
        {
            return $query->where('slug', '=', 'admin-extensions')
                         ->where(Menu::nesty_col('tree'), '=', $admin_tree);
        });

        if ($extensions)
        {
            $extensions->class = 'icon-leaf';
            $extensions->save();
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: down()
     * --------------------------------------------------------------------------
     *
     * Revert the changes to the database.
     *
     * @access   public
     * @return   void
     */
    public function down()
    {
        // Get the admin menu.
        //
        $admin      = Menu::admin_menu();
        $admin_tree = $admin->{Menu::nesty_col('tree')};

        // Update groups list class.
        //
        $extensions = Menu::find(function($query) use ($admin_tree)
        {
            return $query->where('slug', '=', 'admin-extensions')
                         ->where(Menu::nesty_col('tree'), '=', $admin_tree);
        });

        if ($extensions)
        {
            $extensions->class = '';
            $extensions->save();
        }
    }
}

/* End of file 2012_09_08_105000_add_class_to_menus.php */
/* Location: ./platform/extensions/platform/extensions/migrations/2012_09_08_105000_add_class_to_menus.php */