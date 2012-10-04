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
 * Localisation timezones install class
 * --------------------------------------------------------------------------
 * 
 * Timezones installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Install_Timezones
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
         * # 1) Create the timezones table.
         * --------------------------------------------------------------------------
         */
        Schema::create('timezones', function($table){
            $table->increments('id')->unsigned();
            $table->string('timezone');
            $table->string('gmt');
            $table->integer('offset')->default(0);
            $table->integer('default')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Insert the timezones into the database.
         * --------------------------------------------------------------------------
         */
        // Read the json file.
        //
        /*$file = json_decode( File::get( __DIR__ . DS . 'timezones.json' ), true );

        // Loop through the timezones.
        //
        $timezones = array();
        $default = null;
        foreach ( $file as $currency )
        {
            $timezones[] = array(
                'timezone'   => $currency['timezone'],
                'gmt'        => $currency['gmt'],
                'offset'     => $currency['offset'],
                'default'    => ( isset( $currency['default'] ) ? 1 : 0),
                'status'     => ( isset( $currency['status'] ) ? $currency['status'] : 0),
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime
            );

            // Is this a default timezone ?
            //
            if ( isset( $currency['default'] ) )
            {
                $default = $currency['code'];
            }
        }

        // Insert the timezones into the database.
        //
        DB::table('timezones')->insert( $timezones );

        // If we have a default timezone, set it has the default.
        //
        if ( ! is_null( $default ) )
        {
            // Set it as the default currency.
            //
            DB::table('settings')->insert(array(
                'extension' => 'localisation',
                'type'      => 'site',
                'name'      => 'timezone',
                'value'     => $default
            ));
        }*/


        /*
         * --------------------------------------------------------------------------
         * # 3) Create the menus.
         * --------------------------------------------------------------------------
         */
        // Admin > System > Localisation > Languages
        //
        $localisation_menu = Menu::find('admin-localisation');
        $timezones_menu = new Menu(array(
            'name'          => 'Timezones',
            'extension'     => 'timezones',
            'slug'          => 'admin-timezones',
            'uri'           => 'localisation/timezones',
            'user_editable' => 1,
            'status'        => 1
        ));
        $timezones_menu->last_child_of( $localisation_menu );
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
        // Delete the timezones table.
        //
        Schema::drop('timezones');

        // Delete the record from the settings table.
        //  
        DB::table('settings')->where('extension', '=', 'localisation')->where('name', '=', 'timezone')->delete();

        // Delete the menu.
        //
        if ( $menu = Menu::find('admin-timezones') )
        {
            $menu->delete();
        }
    }
}
