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
class Users_v1_0_1
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

        // Update the users link.
        //
        $users = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-users')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($users)
        {
            $users->class = 'icon-user';
            $users->save();
        }

        // Update the users list link.
        //
        $users_list = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-users-list')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($users_list)
        {
            $users_list->class = 'icon-user';
            $users_list->save();
        }

        // Update the groups list link.
        //
        $groups_list = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-groups-list')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($groups_list)
        {
            $groups_list->class = 'icon-user';
            $groups_list->save();
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

        // Update the users link.
        //
        $users = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-users')
                        ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($users)
        {
            $users->class = '';
            $users->save();
        }

        // Update the users list link.
        //
        $users_list = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-users-list')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($users_list)
        {
            $users_list->class = '';
            $users_list->save();
        }

        // Update the groups list link.
        //
        $groups_list = Menu::find(function($query) use ($admin_menu_id)
        {
            return $query->where('slug', '=', 'admin-groups-list')
                         ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
        });

        if ($groups_list)
        {
            $groups_list->class = '';
            $groups_list->save();
        }
    }
}
