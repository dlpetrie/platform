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


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Platform\Menus\Menu;


/**
 * --------------------------------------------------------------------------
 * Install Class v1.0.1
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
class Extensions_v1_0_1
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
        $admin_menu    = Menu::admin_menu();
        $admin_menu_id = $admin_menu->{Menu::nesty_col('tree')};

        // Update the extensions link.
        //
        $extensions = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-extensions')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
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
        /*
         * --------------------------------------------------------------------------
         * # 1) Update the menu items.
         * --------------------------------------------------------------------------
         */
        // Get the admin menu.
        //
        $admin_menu    = Menu::admin_menu();
        $admin_menu_id = $admin_menu->{Menu::nesty_col('tree')};

        // Update the extensions link.
        //
        $extensions = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-extensions')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($extensions)
        {
            $extensions->class = '';
            $extensions->save();
        }
    }
}
