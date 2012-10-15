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


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Theme\Theme as BundleTheme,
    Platform\Themes\Theme;


/**
 * --------------------------------------------------------------------------
 * Themes > API Class
 * --------------------------------------------------------------------------
 * 
 * API class to manage themes.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Themes_API_Themes_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns either all themes in all types, all themes of a particular type, 
     * or a particular theme.
     *
     *  <code>
     *      $all_themes_all_types = API::get('themes');
     *      $all_frontend         = API::get('themes/frontend');
     *      $all_backend          = API::get('themes/backend');
     *      $default_backend      = API::get('themes/backend/default');
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function get_index($type = false, $name = false)
    {
        // Do we want to get all the themes ?
        //
        if ($type == false)
        {
            // Initiate an empty array.
            //
            $themes = array();

            // Loop through all theme types.
            //
            foreach (Theme::types() as $type)
            {
                // Get all the themes from this type.
                //
                if ($theme = Theme::fetch($type))
                {
                    $themes[ $type ] = $theme;
                }
            }

            // Do we have themes ?
            //
            if (count($themes) > 0)
            {
                // We do, return them.
                //
                return new Response($themes);
            }
        }

        // No we maybe want to return all the themes of a particular type
        // or maybe a particular theme.
        //
        else
        {
            // Are we returning theme only ?
            //
            if ($name != false)
            {
                // Try to find this theme.
                //
                if ($theme = Theme::fetch($type, $name) and is_array($theme))
                {
                    // We found it !
                    //
                    return new Response($theme);
                }

                // Theme was not found.
                //
                return new Response(array(
                    'message' => Lang::line('themes::messages.not_found')->get()
                ), API::STATUS_NOT_FOUND);
            }

            // If we have an array of themes for the given type.
            //
            if ($themes = Theme::fetch($type) and is_array($themes))
            {
                return new Response($themes);
            }
        }

        // No themes found.
        //
        return new Response(array(
            'message' => Lang::line('themes::messages.no_themes_found')->get()
        ), API::STATUS_NOT_FOUND);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_options()
     * --------------------------------------------------------------------------
     *
     * Returns an array of theme options for the given theme.
     *
     *  <code>
     *      $options = API::get('themes/frontend/default/options');
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function get_options($type, $name)
    {
        // Make sure the theme really exists.
        //
        if ( ! $theme = Theme::fetch($type, $name))
        {
            return new Response(array(
                'message' => Lang::line('themes::messages.not_found')->get()
            ), API::STATUS_NOT_FOUND);
        }

        $theme_options = Theme::find(function($query) use ($type, $name)
        {
            return $query->where('type', '=', $type)
                         ->where('theme', '=', $name);
        });

        if ($theme_options === null)
        {
            $theme_options = new Theme(array(
                'type'    => $type,
                'theme'   => $name,
                'options' => array(),
            ));
        }

        // Return the options
        return new Response($theme_options);
    }


    /**
     * Updates theme options for a given theme.
     *
     *  <code>
     *      API::put('themes/backend/default/options', $options);
     *  </code>
     *
     * @param   string  $type
     * @param   string  $name
     * @return  Response
     */
    public function put_options($type, $name)
    {
        if ( ! $theme_info = Theme::fetch($type, $name))
        {
            return new Response(array(
                'message' => Lang::line('themes::messages.not_found')->get(),
            ), API::STATUS_NOT_FOUND);
        }

        // Flag we'll use later for the
        // API status
        $exists = true;

        // Find a theme option entity
        $theme  = Theme::find(function($query) use ($type, $name)
        {
            return $query->where('type', '=', $type)
                         ->where('theme', '=', $name);
        });

        // Create if non-existent
        if ($theme === null)
        {
            $exists       = false;
            $theme        = new Theme();
            $theme->type  = $type;
            $theme->theme = $name;
        }

        // Merge the default options with the posted ones
        $theme->options = array_replace_recursive(array_get($theme_info, 'options'), Input::get('options'));
        $theme->status  = Input::get('status');

        if ($theme->save())
        {
            return new Response($theme, ($exists === true) ? API::STATUS_OK : API::STATUS_CREATED);
        }
        else
        {
            return new Response(array(
                    'message' => 'Updated theme.',
                    'errors'  => ($theme->validation()->errors->has()) ? $user->validation()->errors->all() : array(),
                    ), ($theme->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
        }
    }

    public function post_update()
    {
        // make sure type and theme were passed
        if ( ! Input::get('type') or ! Input::get('theme'))
        {
            return array(
                'status'  => false,
                'message' => Lang::line('themes::messages.errors.theme_and_type_required')->get(),
            );
        }

        if ( ! is_array(Input::get('options')) and Input::get('options') != NULL )
        {
            return array(
                'status'  => false,
                'message' => Lang::line('themes::messages.errors.invalid_options')->get(),
            );
        }

        // reformat options for database input to reflect same json string as theme.info
        $options = (Input::get('options')) ?: array();

        $theme = Theme::fetch(Input::get('type'), Input::get('theme'));

        $options = array_replace_recursive($theme['options'], $options);

        if (Input::get('id'))
        {
            // theme_options exist - update it
            $theme = new Theme(array(
                'id'      => Input::get('id'),
                'type'    => Input::get('type'),
                'theme'   => Input::get('theme'),
                'options' => $options,
                'status'  => Input::get('status')
            ));
        }
        else
        {
            // theme_options do not exist - create it
            $theme = new Theme(array(
                'type'    => Input::get('type'),
                'theme'   => Input::get('theme'),
                'options' => $options,
                'status'  => Input::get('status')
            ));
        }

        if ($theme->save())
        {
            return array(
                'status'  => true,
                'message' => Lang::line('themes::messages.success.update')->get(),
            );
        }

        return array(
            'status'  => false,
            'message' => Lang::line('themes.messages.errors.update')->get(),
        );
    }
}
