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
 * Install Class v1.0.0
 * --------------------------------------------------------------------------
 * 
 * Menus installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 */
class Menus_v1_0_0
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
         * # 1) Create the menus table.
         * --------------------------------------------------------------------------
         */
        Schema::create('menus', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('extension')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('uri')->nullable();
            $table->boolean('target')->nullable();
            $table->integer('visibility')->default(0);
            $table->boolean('secure')->default(0);
            $table->boolean('user_editable')->default(0);
            $table->integer('lft');
            $table->integer('rgt');
            $table->integer('menu_id');
            $table->boolean('status');
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Create the menu items.
         * --------------------------------------------------------------------------
         */
        // Create the admin menu.
        //
        $admin = Menu::admin_menu();

        // Create the system link.
        //
        $system = new Menu(array(
            'name'          => 'System',
            'extension'     => '',
            'slug'          => 'admin-system',
            'uri'           => 'settings',
            'user_editable' => 0,
            'status'        => 1
        ));
        $system->last_child_of($admin);

        // Create the menus link.
        //
        $menus = new Menu(array(
            'name'          => 'Menus',
            'extension'     => 'menus',
            'slug'          => 'admin-menus',
            'uri'           => 'menus',
            'user_editable' => 0,
            'status'        => 1
        ));
        $menus->last_child_of($system);

        // Create the main link.
        //
        $main = Menu::main_menu();

        // Create the home link.
        //
        $home = new Menu(array(
            'name'          => 'Home',
            'extension'     => '',
            'slug'          => 'main-home',
            'uri'           => '',
            'visibility'    => 0,
            'user_editable' => 1,
            'status'        => 1
        ));
        $home->last_child_of($main);

        // Create the login link.
        //
        $login = new Menu(array(
            'name'          => 'Login',
            'extension'     => '',
            'slug'          => 'main-login',
            'uri'           => 'login',
            'visibility'    => 2,
            'user_editable' => 1,
            'status'        => 1
        ));
        $login->last_child_of($main);

        // Create the logout link.
        //
        $logout = new Menu(array(
            'name'          => 'Logout',
            'extension'     => '',
            'slug'          => 'main-logout',
            'uri'           => 'logout',
            'visibility'    => 1,
            'user_editable' => 1,
            'status'        => 1
        ));
        $logout->last_child_of($main);

        // Create the register link.
        //
        $register = new Menu(array(
            'name'          => 'Register',
            'extension'     => '',
            'slug'          => 'main-register',
            'uri'           => 'register',
            'visibility'    => 2,
            'user_editable' => 1,
            'status'        => 1
        ));
        $register->last_child_of($main);

        // Create the admin dashboard link.
        //
        $register = new Menu(array(
            'name'          => 'Admin Dashboard',
            'extension'     => '',
            'slug'          => 'main-admin-dashboard',
            'uri'           => 'dashboard',
            'visibility'    => 3,
            'user_editable' => 1,
            'status'        => 1
        ));
        $register->last_child_of($main);
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
        Schema::drop('menus');
    }
}

/* End of file 2012_05_15_045038_install.php */
/* Location: ./platform/extensions/platform/menus/migrations/2012_05_15_045038_install.php */
