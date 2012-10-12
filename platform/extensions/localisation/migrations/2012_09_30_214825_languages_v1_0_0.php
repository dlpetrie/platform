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
 * Languages Install Class v1.0.0
 * --------------------------------------------------------------------------
 * 
 * Languages installation.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_Languages_v1_0_0
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
         * # 1) Create the language table.
         * --------------------------------------------------------------------------
         */
        Schema::create('languages', function($table){
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->string('code', 5);
            $table->string('locale');
            $table->integer('default')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });


        /*
         * --------------------------------------------------------------------------
         * # 2) Insert the languages.
         * --------------------------------------------------------------------------
         */
        // Define a default language, just in case.
        //
        $default = 'en';

        // Read the json file.
        //
        $file = json_decode(File::get(__DIR__ . DS . 'data' . DS . 'languages.json'), true);

        // Loop through the languages.
        //
        $languages = array();
        foreach ($file as $language)
        {
            $languages[] = array(
                'name'       => $language['name'],
                'slug'       => \Str::slug($language['name']),
                'code'       => strtoupper($language['code']),
                'locale'     => $language['locale'],
                'default'    => ( isset($language['default']) ? 1 : 0 ),
                'status'     => ( isset($language['status']) ? $language['status'] : 1 ),
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime
            );

            // Is this a default language ?
            //
            if (isset($language['default']))
            {
                // Mark it as the default then.
                //
                $default = $language['code'];
            }
        }

        // Insert the languages into the database.
        //
        DB::table('languages')->insert($languages);

        // Set it as the default language.
        //
        DB::table('settings')->insert(array(
            'extension' => 'localisation',
            'type'      => 'site',
            'name'      => 'language',
            'value'     => strtoupper($default)
        ));


        /*
         * --------------------------------------------------------------------------
         * # 3) Create the menus.
         * --------------------------------------------------------------------------
         */
        // Admin > System > Localisation > Languages
        //
        $localisation_menu = Menu::find('admin-localisation');
        $languages_menu = new Menu(array(
            'name'          => 'Languages',
            'extension'     => 'languages',
            'slug'          => 'admin-languages',
            'uri'           => 'localisation/languages',
            'user_editable' => 1,
            'status'        => 1
        ));
        $languages_menu->last_child_of($localisation_menu);
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
        // Delete the languages table.
        //
        Schema::drop('languages');

        // Delete the record from the settings table.
        //  
        DB::table('settings')->where('extension', '=', 'localisation')->where('name', '=', 'language')->delete();

        // Delete the menu.
        //
        if ($menu = Menu::find('admin-languages'))
        {
            $menu->delete();
        }
    }
}
