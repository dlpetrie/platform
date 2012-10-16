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

namespace Platform\Themes;


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Config,
    Crud,
    Str,
    Theme as BundleTheme;


/**
 * --------------------------------------------------------------------------
 * Themes > Theme Model
 * --------------------------------------------------------------------------
 *
 * The themes model class.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Theme extends Crud
{
    /**
     * The name of the table associated with the model.
     * If left null, the table name will become the the plural of
     * the class name: user => users
     *
     * @access   protected
     * @var      string
     */
    protected static $_table = 'theme_options';

    /**
     * The path for the theme_options.css file.
     *
     * @access   protected
     * @var      string
     */
    protected static $_filepath;

    /**
     * Content for the theme_options.css file.
     *
     * @access   protected
     * @var      string
     */
    protected static $_css_content;

    /**
     * Array of theme types
     *
     * @access   protected
     * @var      array
     */
    protected static $_types = array('backend', 'frontend');


    /**
     * --------------------------------------------------------------------------
     * Function: prep_attributes()
     * --------------------------------------------------------------------------
     *
     * Called right after validation before inserting/updating to the database
     *
     * @access   public
     * @param    array
     * @return   array
     */
    protected function prep_attributes($attributes)
    {
        // Generate css file contents.
        //
        $options = $attributes['options'];

        // Loop through the options.
        //
        foreach ($options as $id => $option)
        {
            $selector = $options[$id]['selector'];
            $styles = '';
            foreach ($option['styles'] as $attribute => $value)
            {
                $styles .= "\t" . $attribute . ': ' . $value . ';' . "\n";
            }
            static::$_css_content .= $selector . ' {' . "\n" . $styles . '}' . "\n\n";
        }

        // Get compile dir from theme bundle.
        //
        $compile_dir = str_finish(Config::get('theme::theme.compile_directory'), DS);

        // Set path for css file.
        //
        static::$_filepath = BundleTheme::directory() . $compile_dir . $attributes['type'] . DS . $attributes['theme'] . DS . 'assets' . DS . 'css' . DS . 'theme_options.css';

        // Encode options for database storage.
        //
        $attributes['options'] = json_encode($attributes['options']);

        // Return the attributes.
        //
        return $attributes;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: after_insert()
     * --------------------------------------------------------------------------
     *
     * Gets call after the insert() query is exectuted to modify the result.
     *
     * Must return a proper result.
     *
     * @access   public
     * @param    array
     * @return   array
     */
    protected function after_insert($result)
    {
        if ($result)
        {
            // Find css file and rewrite contents.
            //
            file_put_contents(static::$_filepath, static::$_css_content);
        }

        return $result;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: after_update()
     * --------------------------------------------------------------------------
     *
     * Gets call after the update() query is exectuted to modify the result
     *
     * Must return a proper result.
     *
     * @access   public
     * @param    array
     * @return   array
     */
    protected function after_update($result)
    {
        if ($result)
        {
            // Find css file and rewrite contents.
            //
            file_put_contents(static::$_filepath, static::$_css_content);
        }

        return $result;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: after_find()
     * --------------------------------------------------------------------------
     *
     * Gets call after the find() query is exectuted to modify the result.
     * Must return a proper result.
     *
     * @access   protected
     * @param    array
     * @return   array
     */
    protected function after_find($result)
    {
        if ($result)
        {
            $result->options = ($result->options) ? json_decode($result->options) : array();
            $result->status = (bool) $result->status;
        }

        return $result;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: fetch()
     * --------------------------------------------------------------------------
     *
     * Fetches one or more themes in a given type (frontend / backend).
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   array
     */
    public static function fetch($type, $name = null)
    {
        // Get the list of themes names.
        //
        $theme_list = BundleTheme::all($type);

        // Get the current active theme for this theme type.
        //
        $active = \Platform::get('themes.theme.' . $type);

        // Returning one theme ?
        //
        if ( ! is_null($name))
        {
            // Check if the theme exists.
            //
            if ( ! in_array($name, $theme_list))
            {
                return false;
            }

            // Return the theme information.
            //
            return array_merge(array(
                'theme'       => $name,
                'name'        => Str::title($name),
                'description' => null,
                'author'      => null,
                'version'     => '1.0',
                'active'      => ( $active === $name ? true : false ),
                'thumbnail'   => static::thumbnail($type, $name)
            ), BundleTheme::info($type . DS . $name));
        }

        // Initiate an empty array.
        //
        $themes = array();

        // Loop through the themes names list.
        //
        foreach ($theme_list as $theme)
        {
            $themes[ $theme ] = array_merge(array(
                'theme'       => $theme,
                'name'        => Str::title($theme),
                'description' => null,
                'author'      => null,
                'version'     => '1.0',
                'active'      => ( $active === $theme ? true : false ),
                'thumbnail'   => static::thumbnail($type, $theme)
            ), BundleTheme::info($type . DS . $theme));
        }

        // Return the themes.
        //
        return $themes;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: types()
     * --------------------------------------------------------------------------
     *
     * Returns an array of theme types.
     *
     * @access   public
     * @return   array
     */
    public static function types()
    {
        return static::$_types;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: thumbnail()
     * --------------------------------------------------------------------------
     *
     * Returns the theme thumbnail url.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   string
     */
    public static function thumbnail($type, $theme)
    {
        return BundleTheme\Asset::url(str_finish(BundleTheme::directory(), DS) . $type . DS . $theme . DS . 'assets' . DS . 'img' . DS . 'theme-thumbnail.png');
    }
}
