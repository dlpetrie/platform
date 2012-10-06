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
 * Return the language lines.
 * --------------------------------------------------------------------------
 */
return array(
    /*
     * -----------------------------------------
     * Per action messages.
     * -----------------------------------------
     */
    'install' => array(
        'success' => 'Extension <b>:extension</b> was successfully installed.',
        'fail'    => 'Extension <b>:extension</b> can\'t be installed.'
    ),
    'uninstall' => array(
        'success' => 'Extension <b>:extension</b> was successfully uninstalled.',
        'fail'    => 'Extension <b>:extension</b> can\'t be uninstalled.'
    ),
    'enable' => array(
        'success' => 'Extension <b>:extension</b> was successfully enabled.',
        'fail'    => 'Extension <b>:extension</b> can\'t be enabled.'
    ),
    'disable' => array(
        'success' => 'Extension <b>:extension</b> was successfully disabled.',
        'fail'    => 'Extension <b>:extension</b> can\'t be disabled.'
    ),
    'update' => array(
        'success' => 'Extension <b>:extension</b> was successfully updated.'
    ),


    /*
     * -----------------------------------------
     * Other messages.
     * -----------------------------------------
     */
    'not_found'         => 'Extension <b>:extension</b> was not found !',
    'missing_files'     => 'Extension <b>:extension</b> required files are missing !',
    'invalid_file'      => 'Extension <b>:extension</b> doesn\'t have a valid extension.php file',
    'invalid_routes'    => 'Extension <b>:extension</b> "routes" must be a function / closure',
    'invalid_listeners' => 'Extension <b>:extension</b> "listeners" must be a function / closure',
    'invalid_filter'    => 'Invalid extension filter provided.',
    'dependencies'      => 'There is an error with this extension dependencies !',
    'is_core'           => 'This is a core extension, therefore you can\'t do any changes to it',
    'required'          => 'This extension is required, therefore you can\'t do any changes to it.',
    'requires'          => 'Please make sure all the extensions listed above are installed and enabled.'
);
