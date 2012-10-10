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
 * Return the extension data.
 * --------------------------------------------------------------------------
 */
return array(
    /*
     * -----------------------------------------
     * Extension information.
     * -----------------------------------------
     */
    'info' => array(
        'name'        => 'Localisation',
        'author'      => 'Cartalyst LLC',
        'description' => 'Manage your system languages, countries, currencies and timezones.',
        'version'     => '1.0',
        'is_core'     => false
    ),


    /*
     * -----------------------------------------
     * Extension dependencies.
     * -----------------------------------------
     */
    'dependencies' => array(
        'menus',
        'settings'
    ),


    /*
     * -----------------------------------------
     * Rules
     * -----------------------------------------
     */
    'rules' => array(
        'localisation::admin.countries@index',
        'localisation::admin.countries@view',
        'localisation::admin.countries@create',
        'localisation::admin.countries@delete',

        'localisation::admin.currencies@index',
        'localisation::admin.currencies@view',
        'localisation::admin.currencies@create',
        'localisation::admin.currencies@delete',
        
        'localisation::admin.languages@index',
        'localisation::admin.languages@view',
        'localisation::admin.languages@create',
        'localisation::admin.languages@delete',
        #'localisation::admin.timezones@index'
    )
);
