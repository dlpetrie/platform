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
     * Returns either all themes in all types, all themes of a particular type
     * and a particular theme of a particular type.
     *
     *  <code>
     *      $all_themes_all_types  = API::get('themes');
     *      $all_frontend_themes   = API::get('themes/frontend');
     *      $all_backend_themes    = API::get('themes/backend');
     *      $default_backend_theme = API::get('themes/backend/default');
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function get_index($type = false, $name = false)
    {
        // Get the inputs.
        //
        $organize = Input::get('organize', false);

        // Initiate an empty array to store the themes.
        //
        $themes = array();

        // We want a particular theme ?
        //
        if ($type != false && $name != false)
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
                'message' => Lang::line('themes::messages.not_found', array('theme' => $name))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // We want all the themes based on a particular theme type ?
        //
        if ($type != false)
        {
            // Get all the themes for this theme type.
            //
            $themes = Theme::fetch($type);
        }

        // We want to return all the themes.
        //
        else
        {
            // Loop through all the theme types.
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
        }

        // Do we want to organize the themes ?
        //
        if ($organize)
        {
            // Initiate an empty array.
            //
            $organized = array();

            // Loop through the themes.
            //
            foreach($themes as $theme)
            {
                $organized[ $theme['theme'] ] = $theme['name'];
            }

            // Make sure we return the organized themes.
            //
            $themes = $organized;
        }

        // Check if we have themes.
        //
        if (count($themes) > 0)
        {
            // Return the themes.
            //
            return new Response($themes);
        }

        // No themes were found.
        //
        return new Response(array(
            'message' => Lang::line('themes::messages.no_themes_found')->get()
        ), API::STATUS_NOT_FOUND);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_active()
     * --------------------------------------------------------------------------
     *
     * Returns the currenct active theme of a particular theme type.
     *
     * You can provide as the last parameter a default theme, in case it fails to
     * find the default theme for the provided type.
     *
     *  <code>
     *      API::get('themes/active/frontend');
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function get_active($type, $default = 'default')
    {
        return new Response(Platform::get('themes.theme.' . $type, $default));
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_activate()
     * --------------------------------------------------------------------------
     *
     * Activates a provided theme by the particular them type.
     *
     *  <code>
     *      API::put('themes/activate/frontend/default');
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function put_activate($type, $name)
    {
        // Check if the theme exists.
        //
        if ( ! $theme_info = Theme::fetch($type, $name))
        {
            // Theme was not found.
            //
            return new Response(array(
                'message' => Lang::line('themes::messages.not_found', array('theme' => $type . '\\' . $name))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Is this the current active theme ?
        //
        if ($info['active'])
        {
            // Theme is already activated.
            //
            return new Response(array(
                'message' => Lang::line('themes::messages.activate.already', array('theme' => $type . '\\' . $name))->get()
            ), API::STATUS_BAD_REQUEST);
        }

        try
        {
            // Make the request.
            //
            API::put('settings', array(
                'settings' => array(
                    // Values
                    //
                    'extension' => 'themes',
                    'type'      => 'theme',
                    'name'      => $type,
                    'value'     => $name,

                    // Validation
                    //
                    'validation' => array(
                        $type  => 'required'
                    )
                )
            ));

            // Theme was activated with success.
            //
            return new Response(array(
                'message' => Lang::line('themes::messages.activate.success', array('theme' => $type . '\\' . $name))->get()
            ));
        }
        catch (Exception $e)
        {
            // Theme was not activated.
            //
            return new Response(array(
                'message' => Lang::line('themes::messages.activate.fail', array('theme' => $type . '\\' . $name))->get()
            ), API::STATUS_BAD_REQUEST);
        }
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
        // Check if the theme exists.
        //
        if ( ! $theme_info = Theme::fetch($type, $name))
        {
            return new Response(array(
                'message' => Lang::line('themes::messages.not_found', array('theme' => $type . '\\' . $name))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Get this theme options.
        //
        $theme_options = Theme::find(function($query) use ($type, $name)
        {
            return $query->where('type', '=', $type)->where('theme', '=', $name);
        });

        // If we don't have theme options.
        //
        if (is_null($theme_options))
        {
            // Prepare a fallback array.
            //
            $theme_options = new Theme(array(
                'type'    => $type,
                'theme'   => $name,
                'options' => array()
            ));
        }

        // Return the options.
        //
        return new Response($theme_options);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_options()
     * --------------------------------------------------------------------------
     *
     * Updates theme options for a given theme.
     *
     *  <code>
     *      API::put('themes/backend/default/options', $options);
     *  </code>
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   Response
     */
    public function put_options($type, $name)
    {
        // Check if the theme exists.
        //
        if ( ! $theme_info = Theme::fetch($type, $name))
        {
            // Theme was not found.
            //
            return new Response(array(
                'message' => Lang::line('themes::messages.not_found', array('theme' => $type . '\\' . $name))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Flag we'll use later for the API status.
        //
        $exists = true;

        // Find a theme option entity.
        //
        $theme_model  = Theme::find(function($query) use ($type, $name)
        {
            return $query->where('type', '=', $type)
                         ->where('theme', '=', $name);
        });

        // Create if non-existent
        if (is_null($theme_model))
        {
            $exists             = false;
            $theme_model        = new Theme();
            $theme_model->type  = $type;
            $theme_model->theme = $name;
        }

        // Merge the default options with the posted ones.
        //
        $theme_model->options = array_replace_recursive(array_get($theme_info, 'options'), Input::get('options'));
        $theme_model->status  = Input::get('status');

        // Update the theme.
        //
        if ($theme_model->save())
        {
            return new Response($name, ($exists ? API::STATUS_OK : API::STATUS_CREATED));
        }

        // An error ocurred while updating the theme.
        //
        else
        {
            return new Response(array(
                'message' => Lang::line('themes::messages.update.fail', array('theme' => $type . '\\' . $name))->get(),
                'errors'  => ($theme_model->validation()->errors->has()) ? $theme_model->validation()->errors->all() : array()
            ), ($theme_model->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
        }
    }
}
