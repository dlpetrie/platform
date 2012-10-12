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
    'Platform\\Localisation\\Widgets' => __DIR__ . DS . 'widgets',
    'Platform\\Localisation'          => __DIR__ . DS . 'models'
));


/*
 * --------------------------------------------------------------------------
 * Include our helpers file.
 * --------------------------------------------------------------------------
 */
require_once __DIR__ . DS . 'helpers.php';


/*
 * --------------------------------------------------------------------------
 * Check if the extension is enabled !
 * --------------------------------------------------------------------------
 */
if (Platform::extensions_manager()->is_enabled('localisation'))
{
    // Get all the settings.
    //
    $localisation = array();
    foreach (DB::table('settings')->where('extension', '=', 'localisation')->get() as $local)
    {
        $localisation[ $local->name ] = $local->value;
    }

    // Set the currency.
    //
    Config::set('application.currency', strtolower($localisation['currency']));

    // Set the language.
    //
    Config::set('application.language', strtolower($localisation['language']));

    // Set the timezone.
    //
    Config::set('application.timezone', strtolower($localisation['timezone']));
}
