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
 * @version    1.1.0
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
 * Countries Install Class v1.0.0
 * --------------------------------------------------------------------------
 * 
 * Countries installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Countries_v1_0_0
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
         * # 1) Create the countries table.
         * --------------------------------------------------------------------------
         */
        Schema::create('countries', function($table){
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('iso_code_2')->nullable();
            $table->string('iso_code_3')->nullable();
            $table->string('iso_code_numeric_3')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('currency')->nullable();
            $table->integer('cdh_id')->nullable();
            $table->integer('default')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Populate the countries table.
         * --------------------------------------------------------------------------
         */
        // Read the countries from the CSV file.
        //
        $file = json_decode(File::get(__DIR__ . DS . 'data' . DS . 'countries.json'), true);

        // Loop through the countries.
        //
        $countries = array();
        foreach ($file as $country)
        {
            // Make sure we have these values.
            //
            $countries[] = array(
                'name'               => $country['name'],
                'slug'               => \Str::slug($country['name']),
                'iso_code_2'         => $country['iso_code_2'],
                'iso_code_3'         => $country['iso_code_3'],
                'iso_code_numeric_3' => $country['iso_code_numeric_3'],
                'region'             => $country['region'],
                'subregion'          => $country['subregion'],
                'currency'           => $country['currency'],
                'cdh_id'             => $country['cdh_id'],
                'created_at'         => new \DateTime,
                'updated_at'         => new \DateTime
            );
        }

        // Insert the countries into the database.
        //
        DB::table('countries')->insert( $countries );


        /*
         * --------------------------------------------------------------------------
         * # 3) Set the default country.
         * --------------------------------------------------------------------------
         */
        DB::table('countries')->where('iso_code_2', '=', 'GB')->update(array('default' => 1));
        DB::table('settings')->insert(array(
            'extension' => 'localisation',
            'type'      => 'site',
            'name'      => 'country',
            'value'     => 'gb'
        ));


        /*
         * --------------------------------------------------------------------------
         * # 4) Create the menu.
         * --------------------------------------------------------------------------
         */
        // Admin > System > Localisation > Countries
        //
        $localisation_menu = Menu::find('admin-localisation');
        $countries_menu = new Menu(array(
            'name'          => 'Countries',
            'extension'     => 'countries',
            'slug'          => 'admin-countries',
            'uri'           => 'localisation/countries',
            'user_editable' => 1,
            'status'        => 1
        ));
        $countries_menu->last_child_of($localisation_menu);
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
        // Delete the countries table.
        //
        Schema::drop('countries');

        // Delete the record from the settings table.
        //  
        DB::table('settings')->where('extension', '=', 'localisation')->where('name', '=', 'country')->delete();

        // Delete the menu.
        //
        if ($menu = Menu::find('admin-countries'))
        {
            $menu->delete();
        }
    }
}
