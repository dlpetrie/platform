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
use Laravel\CLI\Command,
    Platform\Menus\Menu;


/**
 * --------------------------------------------------------------------------
 * Install Class v1.0.0
 * --------------------------------------------------------------------------
 * 
 * Users installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 */
class Users_v1_0_0
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
         * # 1) Create the necessary tables.
         * --------------------------------------------------------------------------
         */
        // Run Sentry migrations.
        //
        Command::run(array('migrate', 'sentry'));

        // Remove the username column, since we are not using it.
        //
        Schema::table('users', function($table)
        {
            $table->drop_column('username');
        });

        // Create the Sentry default groups.
        //
        Sentry::group()->create(array(
            'name'        => 'admin',
            'permissions' => ''
        ));

        Sentry::group()->create(array(
            'name'        => 'users',
            'permissions' => ''
        ));


        /*
         * --------------------------------------------------------------------------
         * # 2) Create the menus.
         * --------------------------------------------------------------------------
         */
        // Get the Admin menu.
        //
        $admin = Menu::admin_menu();

        // Get the System menu.
        //
        $system = Menu::find('admin-system');

        // Admin > Users
        //
        $users = new Menu(array(
            'name'          => 'Users',
            'extension'     => 'users',
            'slug'          => 'admin-users',
            'uri'           => 'users',
            'user_editable' => 0,
            'status'        => 1,
        ));

        if (is_null($system))
        {
            $users->last_child_of($admin);
        }
        else
        {
            $users->previous_sibling_of($system);
        }

        // Admin > Users > Users list
        //
        $users_list = new Menu(array(
            'name'          => 'Users',
            'extension'     => 'users',
            'slug'          => 'admin-users-list',
            'uri'           => 'users',
            'user_editable' => 0,
            'status'        => 1,
        ));
        $users_list->last_child_of($users);

        // Admin > Users > Groups list
        //
        $groups = new Menu(array(
            'name'          => 'Groups',
            'extension'     => 'users',
            'slug'          => 'admin-groups-list',
            'uri'           => 'users/groups',
            'user_editable' => 0,
            'status'        => 1,
        ));
        $groups->last_child_of($users);



        /*
         * --------------------------------------------------------------------------
         * # 3) Configuration settings.
         * --------------------------------------------------------------------------
         */
        $settings = array(
            // Status Disabled
            //
            array(
                'extension' => 'users',
                'type'      => 'status',
                'name'      => 'disabled',
                'value'     => '0'
            ),

            // Status Enabled
            //
            array(
                'extension' => 'users',
                'type'      => 'status',
                'name'      => 'enabled',
                'value'     => '1'
            )
        );

        // Insert the settings into the database.
        //
        DB::table('settings')->insert($settings);
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
         * # 1) Drop the necessary tables.
         * --------------------------------------------------------------------------
         */
        // Normally we'd rollback, but we're resetting
        // as this is the first migration.
        Command::run(array('migrate:reset', 'sentry'));


        /*
         * --------------------------------------------------------------------------
         * # 2) Delete the menus.
         * --------------------------------------------------------------------------
         */
        if ($menu = Menu::find('admin-users'))
        {
            $menu->delete();
        }


        /*
         * --------------------------------------------------------------------------
         * # 3) Drop the configuration settings.
         * --------------------------------------------------------------------------
         */
        DB::table('settings')->where('extension', '=', 'users')->delete();
    }
}
