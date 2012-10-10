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
 * Currencies Install Class v1.0.0
 * --------------------------------------------------------------------------
 * 
 * Currencies installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Currencies_v1_0_0
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
         * # 1) Create the currencies table.
         * --------------------------------------------------------------------------
         */
        Schema::create('currencies', function($table){
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code', 5);
            $table->integer('cdh_id')->nullable();
            $table->integer('default')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Insert the currencies.
         * --------------------------------------------------------------------------
         */
        // Read the json file.
        //
        /*$file = json_decode( File::get( __DIR__ . DS . 'currencies.json' ), true );

        // Loop through the currencies.
        //
        $currencies = array();
        $default = null;
        foreach ( $file as $currency )
        {
            $currencies[] = array(
                'name'       => $currency['name'],
                'code'       => $currency['code'],
                'cdh_id'     => $currency['cdh_id'],
                'default'    => ( isset( $currency['default'] ) ? 1 : 0),
                'status'     => ( isset( $currency['status'] ) ? $currency['status'] : 0),
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime
            );

            // Is this a default currency ?
            //
            if ( isset( $currency['default'] ) )
            {
                $default = $currency['code'];
            }
        }

        // Insert the currencies into the database.
        //
        DB::table('currencies')->insert( $currencies );

        // If we have a default currency, set it has the default.
        //
        if ( ! is_null( $default ) )
        {
            // Set it as the default currency.
            //
            DB::table('settings')->insert(array(
                'extension' => 'localisation',
                'type'      => 'site',
                'name'      => 'currency',
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
        $currencies_menu = new Menu(array(
            'name'          => 'Currencies',
            'extension'     => 'currencies',
            'slug'          => 'admin-currencies',
            'uri'           => 'localisation/currencies',
            'user_editable' => 1,
            'status'        => 1
        ));
        $currencies_menu->last_child_of($localisation_menu);
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
        // Delete the currencies table.
        //
        Schema::drop('currencies');

        // Delete the record from the settings table.
        //  
        DB::table('settings')->where('extension', '=', 'localisation')->where('name', '=', 'currency')->delete();

        // Delete the menu.
        //
        if ($menu = Menu::find('admin-currencies'))
        {
            $menu->delete();
        }
    }
}
