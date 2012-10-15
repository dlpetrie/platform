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
 * Extensions installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 */
class Themes_v1_0_0
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
        Schema::create('theme_options', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('type');
            $table->string('theme');
            $table->text('options')->nullable();
            $table->boolean('status');
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Create the menus.
         * --------------------------------------------------------------------------
         */
        // Get the System menu.
        //
        $system = Menu::find('admin-system');

        // Admin > System > Themes
        //
        $themes = new Menu(array(
            'name'          => 'Themes',
            'extension'     => 'themes',
            'slug'          => 'admin-themes',
            'uri'           => 'themes',
            'user_editable' => 0,
            'status'        => 1
        ));
        $themes->last_child_of($system);

        // Admin > System > Themes > Frontend
        //
        $frontend = new Menu(array(
            'name'          => 'Frontend',
            'extension'     => 'themes',
            'slug'          => 'admin-frontend',
            'uri'           => 'themes/frontend',
            'user_editable' => 0,
            'status'        => 1
        ));
        $frontend->last_child_of($themes);

        // Admin > System > Themes > Backend
        //
        $backend = new Menu(array(
            'name'          => 'Backend',
            'extension'     => 'themes',
            'slug'          => 'admin-backend',
            'uri'           => 'themes/backend',
            'user_editable' => 0,
            'status'        => 1
        ));
        $backend->last_child_of($themes);


        /*
         * --------------------------------------------------------------------------
         * # 3) Configuration settings.
         * --------------------------------------------------------------------------
         */
        $settings = array(
            // Frontend default theme.
            //
            array(
                'extension' => 'themes',
                'type'      => 'theme',
                'name'      => 'frontend',
                'value'     => 'default'
            ),

            // Backend default theme.
            //
            array(
                'extension' => 'themes',
                'type'      => 'theme',
                'name'      => 'backend',
                'value'     => 'default'
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
        Schema::drop('theme_options');


        /*
         * --------------------------------------------------------------------------
         * # 2) Drop the menus.
         * --------------------------------------------------------------------------
         */
        if ($menu = Menu::find('admin-themes'))
        {
            $menu->delete_with_children();
        }


        /*
         * --------------------------------------------------------------------------
         * # 3) Drop the configuration settings.
         * --------------------------------------------------------------------------
         */
        DB::table('settings')->where('extension', '=', 'themes')->delete();
    }
}
