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


/**
 * --------------------------------------------------------------------------
 * Platform Class
 * --------------------------------------------------------------------------
 * 
 * This is the main class, it does most of the heavy work !
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Platform
{
    /**
     * Flag for whether Platform is initalized.
     *
     * @access    protected
     * @var       boolean
     */
    protected static $initialized = false;

    /**
     * Extensions Manager.
     *
     * @access    protected
     * @var       object
     */
    protected static $extensions_manager = null;

    /**
     * Handles system messages for Platform.
     *
     * @access    protected
     * @var       object
     */
    protected static $messages = null;

    /**
     * Holds an array of Platform Widgets.
     *
     * @access    protected
     * @var       array
     */
    protected static $widgets = array();

    /**
     * Holds an array of Platform Plugins.
     *
     * @access    protected
     * @var       array
     */
    protected static $plugins = array();

    /**
     * Holds extension settings.
     *
     * @access    protected
     * @var       array
     */
    protected static $settings = array();


    /**
     * --------------------------------------------------------------------------
     * Function: start()
     * --------------------------------------------------------------------------
     *
     * Starts up Platform.
     *
     * @access   public
     * @return   void
     */
    public static function start()
    {
        // If we have already initialized Platform.
        //
        if ( static::$initialized === true )
        {
            return true;
        }

        // Check if Platform is installed.
        //
        if ( ! static::is_installed() )
        {
            // Start the installer.
            //
            static::start_installer();
        }

        // Platform is installed.
        //
        else
        {
            // Platform was not initialized, but is now.
            //
            static::$initialized = true;

            // Register blade extensions.
            //
            static::register_blade_extensions();

            // Start the modules.
            //
            static::extensions_manager()->start_extensions();
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_installed()
     * --------------------------------------------------------------------------
     *
     * Determines if Platform has been installed or not.
     *
     * @access   public
     * @return   boolean
     */
    public static function is_installed()
    {
        // Check for the database config file.
        //
        if ( ! File::exists( path('app') . 'config' . DS . 'database' . EXT ) )
        {
            if ( is_dir( path('base') . 'installer' ) and ! Request::cli() )
            {
                return false;
            }
            else
            {
                throw new Exception('No database file exists in application/config');
            }
        }

        // List installed extensions, if the count is 0, it is not installed.
        //
        try
        {
            if ( DB::table('extensions')->count() === 0 )
            {
                if ( is_dir( path('base') . 'installer' ) and ! Request::cli() )
                {
                    return false;
                }
                else
                {
                    throw new Exception('No Platform tables exist');
                }
            }
        }
        catch ( Exception $e )
        {
            if ( is_dir( path('base') . 'installer' ) and ! Request::cli() )
            {
                return false;
            }

            throw new Exception('No Platform tables exist');
        }

        // Check for the users table.
        //
        try
        {
            if ( DB::table('users')->count() === 0 )
            {
                if ( is_dir( path('base') . 'installer') and ! Request::cli() )
                {
                    return false;
                }
                else
                {
                    throw new Exception('No Platform users exist');
                }
            }
        }
        catch ( Exception $e )
        {
            if ( is_dir( path('base') . 'installer' ) and ! Request::cli() )
            {
                return false;
            }
            else
            {
                throw new Exception('No Platform users exist');
            }
        }

        // Check if the install directory still exists.
        //
        if ( is_dir( path('base') . 'installer' ) and ! Request::cli() )
        {
            // Initiate the installer.
            //
            Platform::start_installer(false);

            // This is so we can't see other steps rather than step 5 in the installer !!
            //
            Session::put('install_directory', true); // maybe change this to platform.installed
        }

        // Platform is installed.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: start_installer()
     * --------------------------------------------------------------------------
     *
     * Starts the installer module.
     *
     * @access   public
     * @param    boolean
     * @return   void
     */
    public static function start_installer( $redirect = true )
    {
        // Register the installer bundle.
        //
        Bundle::register('installer', array(
            'location' => 'path: ' . path('installer'),
            'handles'  => 'installer'
        ));

        // Start the installer bundle.
        //
        Bundle::start('installer');

        // If we are not in the installer and we want to be redirected.
        //
        if ( ! URI::is('installer|installer/*') and $redirect )
        {
            // Redirect to the installer page.
            //
            Redirect::to('installer')->send();
            exit;
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: extensions_manager()
     * --------------------------------------------------------------------------
     *
     * Retrieves the extensions manager instance.
     *
     * @access   public
     * @return   object
     */
    public static function extensions_manager()
    {
        // Check if we have already initialized our extensions manager.
        //
        if ( is_null( static::$extensions_manager ) )
        {
            static::$extensions_manager = new ExtensionsManager();
        }

        // Return the extensions manager instance.
        //
        return static::$extensions_manager;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: messages()
     * --------------------------------------------------------------------------
     *
     * Return the Platform Messages object
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public static function messages()
    {
        // Check if we have already initialized our messages class.
        //
        if ( is_null( static::$messages ) )
        {
            // Start the messages class.
            //
            static::$messages = Messages::instance();
        }

        // Return the messages instance.
        //
        return static::$messages;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: user()
     * --------------------------------------------------------------------------
     *
     * Gets the Platform User.
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public static function user()
    {
        return Sentry::user();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: register_blade_extensions()
     * --------------------------------------------------------------------------
     *
     * Register Platform extensions for Laravel Blade.
     *
     * @access   protected
     * @return   void
     */
    protected static function register_blade_extensions()
    {
        /**
         * Register @widget with blade.
         *
         *  TODO: add error logging when widget/plugin fails
         * 
         * @return   string
         */
        Blade::extend(function($view)
        {
            $pattern = Blade::matcher('widget');

            return preg_replace($pattern, '<?php echo Platform::widget$2; ?>', $view);
        });

        /**
         * Register @plugin with blade.
         *
         * @return   string
         */
        Blade::extend(function($view)
        {
            $pattern = "/\s*@plugin\s*\(\s*\'(.*?)\'\s*\,\s*\'(.*?)\'\s*\,(.*?)\)/";

            return preg_replace($pattern, '<?php $$2 = Platform::plugin(\'$1\',$3); ?>', $view);
        });

        /**
         * Register @get with blade.
         *
         * @return   string
         */
        Blade::extend(function($view)
        {
            $pattern = "/@get\.([^\s\"<]*)/";

            return preg_replace($pattern, '<?php echo Platform::get(\'$1\'); ?>', $view);
        });

        /**
         * Compile HTML coments
         *
         * @see http://stackoverflow.com/questions/1013499/stripping-html-comments-with-php-but-leaving-conditionals#answer-1013864
         *
         * @return   string
         */
        Blade::extend(function($view)
        {
            $replacements = array(
                '/<!--((?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*)-->(?:\n)?/is' => "<?php /* $1 */ ?>",
                // '/\/\*/' => '', // Not needed as /* can exist in PHP comments
                // '/\*\//' => '', // Is needed because we don't want to close the comments early
            );

            return preg_replace(array_keys($replacements), array_values($replacements), $view);
        });
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get()
     * --------------------------------------------------------------------------
     *
     * Retrieves a setting value by the given setting key.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   mixed
     */
    public static function get( $setting = null, $default = null )
    {
        $settings = explode('.', $setting);
        $extension = array_shift($settings);
        if (count($settings) > 1)
        {
            $type = array_shift($settings);
            $name = array_shift($settings);
        }
        else
        {
            $type = $extension;
            $name = array_shift($settings);
        }

        if ( ! array_key_exists( $extension, static::$settings ) )
        {
            try
            {
                // Find all settings for requested extension
                static::$settings[ $extension ] = API::get('settings', array(
                    'where' => array(
                        array('extension', '=', $extension),
                    ),
                    'organize' => true,
                ));
            }
            catch (APIClientException $e)
            {
                static::$settings[ $extension ] = array();
            }
        }

        // Check if the setting value exists.
        //
        if ( $setting = array_get( static::$settings, $extension . '.' . $type . '.' . $name ) )
        {
            return $setting['value'];
        }

        // Return the default value.
        //
        return value($default);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: widget()
     * --------------------------------------------------------------------------
     *
     * Loads a widget.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public static function widget( $name = null )
    {
        // Get the widget name.
        //
        $name = trim($name);

        // Get all the parameters passed to the widget.
        //
        $parameters = array_slice(func_get_args(), 1);

        // Check if this widget is from an extension.
        //
        if ( strpos($name, '::') === false ):
            return;
        endif;

        // Get the extension path and the action of this widget.
        //
        list($bundle_path, $action) = explode('::', strtolower($name));

        // See if there is a namespace present.
        //
        if (strpos($bundle_path, '.') !== false)
        {
            list ($namespace, $extension) = explode('.', $bundle_path);
        }
        else
        {
            $extension = $bundle_path;
            $namespace = '';
        }

        // Some needed variables.
        //
        $path = explode('.', $action);
        $method = array_pop($path);

        // Prepare the plugin class.
        //
        $class = ucfirst( $namespace ) . '\\' . ucfirst( $extension ) . '\\Widgets\\' . ucfirst( implode('_', $path) );

        // Check if this widget is already initialized.
        //
        if ( array_key_exists( $class, static::$widgets ) )
        {
            $widget = static::$widgets[$class];
        }

        // Plugin is not initialized.
        //
        else
        {
            // Check if the extension is initialized, if not, initiate it.
            //
            ! Bundle::started($extension) and Bundle::start($extension);

            // Check if the plugin class exists.
            //
            if ( ! class_exists( $class ) )
            {
                return; #throw new \Exception('Class: '.$class.' does not exist.');
            }

            // Initialize the plugin class.
            //
            $widget = new $class();

            // Store the object in our widgets array.
            //
            static::$widgets[ $class ] = $widget;
        }

        // Check if the method exists.
        //
        if ( ! method_exists( $class, $method ) ):
            return; #throw new \Exception('Method: '.$method.' does not exist in class: '.$class);
        endif;

        // Execute the widget.
        //
        return call_user_func_array(array($widget, $method), $parameters);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: plugin()
     * --------------------------------------------------------------------------
     *
     * Loads a plugin.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public static function plugin( $name = null )
    {
        // Get the plugin name.
        //
        $name = trim($name);

        // Get all the parameters passed to the plugin.
        //
        $parameters = array_slice(func_get_args(), 1);

        // Check if this plugin is from an extension.
        //
        if ( strpos($name, '::') === false ):
            return;
        endif;

        // Get the extension path and the action of this plugin.
        //
        list($bundle_path, $action) = explode('::', strtolower($name));

        // See if there is a namespace present.
        //
        if (strpos($bundle_path, '.') !== false)
        {
            list($namespace, $extension) = explode('.', $bundle_path);
        }
        else
        {
            $extension = $bundle_path;
            $namespace = '';
        }

        // Some needed variables.
        //
        $path = explode('.', $action);
        $method = array_pop($path);

        // Prepare the plugin class.
        //
        $class = ucfirst( $namespace ) . '\\' . ucfirst( $extension ) . '\\Plugins\\' . ucfirst( implode('_', $path) );

        // Check if this plugin is already initialized.
        //
        if ( array_key_exists( $class, static::$plugins ) )
        {
            $plugin = static::$plugins[ $class ];
        }

        // Plugin is not initialized.
        //
        else
        {
            // Check if the extension is initialized, if not, initiate it.
            //
            ! Bundle::started($extension) and Bundle::start($extension);

            // Check if the plugin class exists.
            //
            if ( ! class_exists( $class ) )
            {
                return; #throw new \Exception('Class: '.$class.' does not exist.');
            }

            // Initialize the plugin class.
            //
            $plugin = new $class();

            // Store the object in our plugins array.
            //
            static::$plugins[ $class ] = $plugin;
        }

        // Check if the method exists.
        //
        if ( ! method_exists( $class, $method ) ):
            return; #throw new \Exception('Method: '.$method.' does not exist in class: '.$class);
        endif;

        // Execute the plugin.
        //
        return call_user_func_array( array( $plugin, $method ), $parameters );
    }


    /**
     * --------------------------------------------------------------------------
     * Function: licence()
     * --------------------------------------------------------------------------
     *
     * Returns the string for a Platform license.
     *
     * If no extension is passed, we assume the default extension is .txt
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public static function license( $file = null )
    {
        // If no file is passed, we return the Platform licence.
        //
        if ( is_null( $file ) )
        {
            return File::get( path('licenses') . DS . 'platform.txt' );
        }

        // No file extension found, use the default one.
        //
        if ( ! pathinfo($file, PATHINFO_EXTENSION) )
        {
            $file.='.txt';
        }

        // Return the license file contents, if the file exists.
        //
        return File::get( path('licenses') . DS . $file);
    }
}

/* End of file platform.php */
/* Location: ./platform/application/platform/platform.php */