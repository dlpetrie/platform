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
    'is_core'  => 'This is a core extension, therefore you can\'t make any change to it',
    'required' => 'This extension is required, therefore you can\'t make any change to it.',
    'requires' => 'Please make sure all the extensions listed above are installed and enabled.',


    /*
     * -----------------------------------------
     * Per action messages.
     * -----------------------------------------
     */
    'install' => array(
        'success' => 'Extension :extension was successfully installed.',
        'fail'    => 'Extension :extension can\'t be installed.'
    ),

    'uninstall' => array(
        'success' => 'Extension :extension was successfully uninstalled.',
        'fail'    => 'Extension :extension can\'t be uninstalled.'
    ),

    'enable' => array(
        'success' => 'Extension :extension was successfully enabled.',
        'fail'    => 'Extension :extension can\'t be enabled.'
    ),

    'disable' => array(
        'success' => 'Extension :extension was successfully disabled.',
        'fail'    => 'Extension :extension can\'t be disabled.'
    ),

    'update' => array(
        'success' => 'Extension :extension was successfully updated.',
        'fail'    => 'Extension :extension can\'t be disabled.'
    ),


    /*
     * -----------------------------------------
     * Error messages.
     * -----------------------------------------
     */
    'error' => array(
        'dependencies'   => 'There is an error with this extension dependencies !',
        'not_found'      => 'Extension :extension was not found !',
        'invalid_filter' => 'Invalid extension filter provided.',
        'bundles'        => 'Every extension.php file must contain a bundles array. None found in :slug'
    )
);

/* End of file messages.php */
/* Location: ./platform/extensions/platform/extensions/language/en/messages.php */