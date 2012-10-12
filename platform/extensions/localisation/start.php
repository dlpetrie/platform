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
 * Register some namespaces.
 * --------------------------------------------------------------------------
 */
Autoloader::namespaces(array(
    'Localisation' => __DIR__ . DS . 'models'
));


/*
 * --------------------------------------------------------------------------
 * Include our helpers file.
 * --------------------------------------------------------------------------
 */
require_once __DIR__ . DS . 'helpers.php';

/*
       $teste = API::get('settings', array(
    'where' => array(
        array('extension', '=', 'localisation'),
        array('name', '=', 'country')
    )
));

       var_dump( $teste );

die;
*/
#Config::set('application.timezone', 'America/Los_Angeles');
/*
// set the language locales and runtime configuration aswell
Config::set('application.language', 'pt');
setlocale(LC_ALL, "pt_PT",  "portuguese");
echo strftime('%A, %d. %B %Y');
*/

// Get the localisation default settings.
//
#$localisation = DB::table('settings')->where('extension', '=', 'localisation')->get();
# tenho de trabalhar este array, para ser mais facil de usar os dados...


/*
 * --------------------------------------------------------------------------
 * Set the language locales and update the configuration at runtime.
 * --------------------------------------------------------------------------
 */
$language = DB::table('languages')->where('default', '=', 1)->first();
Config::set('application.language', strtolower($language->abbreviation));
#setlocale(LC_ALL, explode(', ', $language->locale));


// tests..
#echo strftime('%A, %d. %B %Y');

/*
 * --------------------------------------------------------------------------
 * Initiate the localisation library, so we can make the necessary changes
 * to the runtime configuration.
 * --------------------------------------------------------------------------
 */
#Localisation::init();
