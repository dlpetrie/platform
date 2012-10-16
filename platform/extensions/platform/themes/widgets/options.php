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

namespace Platform\Themes\Widgets;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use API,
    APIClientException,
    Theme;


/**
 * --------------------------------------------------------------------------
 * Themes > Options widget Class
 * --------------------------------------------------------------------------
 *
 * Wigdet class for updating theme options.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Options
{
    /**
     * --------------------------------------------------------------------------
     * Function: css()
     * --------------------------------------------------------------------------
     *
     * Returns the custom theme options.
     *
     * @access   public
     * @return   View
     */
    public function css()
    {
        // Get the active theme type and name.
        //
        $active_parts = explode(DS, ltrim(rtrim(Theme::active(), DS), DS));
        $type         = $active_parts[0];
        $name         = $active_parts[1];
       
        try
        {
            // Get active theme custom options.
            //
            $options = API::get('themes/' . $type . '/' . $name . '/options');
        }
        catch (APIClientException $e)
        {
            Platform::messages()->error($e->getMessage());

            foreach ($e->errors() as $error)
            {
                Platform::messages()->error($error);
            }

            // Options fallback
            $options = array();
        }

        // Get the active status from the database. The admin
        // compiles the theme_options.css file. We need to know
        // if it's actually active before including the file (done
        // in the view).
        $status = array_get($options, 'status', false);

        // Show the page.
        //
        return Theme::make('themes::widgets.theme_options_css')->with('status', $status);
    }
}
