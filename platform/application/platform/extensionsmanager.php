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
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Laravel\Config, Laravel\CLI\Command, Laravel\Database\Schema;


/**
 * --------------------------------------------------------------------------
 * Extensions Manager Class
 * --------------------------------------------------------------------------
 * 
 * A class to manage our extensions.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class ExtensionsManager
{
    /**
     * Stores all the extensions.
     *
     * @access   public
     * @var      array
     */
    public $extensions = array();

    /**
     * Stores all the installed extensions.
     *
     * @access   public
     * @var      object
     */
    public $installed = array();

    /**
     * Stores all the uninstalled extensions.
     *
     * @access   public
     * @var      array
     */
    public $uninstalled = array();

    /**
     * Stores all the enabled extensions.
     *
     * @access   public
     * @var      array
     */
    public $enabled = array();

    /**
     * Stores all the disabled extensions.
     *
     * @access   public
     * @var      array
     */
    public $disabled = array();

    /**
     * Stores each extension dependencies.
     *
     * @access   private
     * @var      array
     */
    private $dependencies = array();

    /**
     * Stores each extension dependents.
     *
     * @access   private
     * @var      array
     */
    private $dependents = array();

    /**
     * Needed flag, to use when checking if the extension can be modified, like
     * installed, uninstalled, enabled or disabled.
     *
     * We can use this flag to turn the checking Off when installing Platform.
     *
     * @access   private
     * @var      boolean
     */
    private $checking = true;


    /**
     * --------------------------------------------------------------------------
     * Function: start_extensions()
     * --------------------------------------------------------------------------
     *
     * Initiate all the installed and enabled extensions.
     *
     * @access   public
     * @return   void
     */
    public function start_extensions()
    {
        // Get all the extensions.
        //
        $this->all();

        // Initiate the enabled extensions.
        //
        foreach ($this->enabled() as $slug => $extension)
        {
            // Check if the extension was started with success.
            //
            if( ! $this->start($slug))
            {
                // Set the warning message.
                //
                Platform::messages()->warning(Lang::line('extensions.missing_files', array('extension' => $slug))->get());
            }
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: start()
     * --------------------------------------------------------------------------
     *
     * Start's an extension by the given slug.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function start($slug = null)
    {
        // Try to get this extension information.
        //
        if( ! $extension = $this->get($slug))
        {
            return false;
        }

        // Check if this extension is already started.
        //
        if ($bundle = array_get($extension, 'bundles.handles', $slug) and Bundle::started($bundle))
        {
            return true;
        }

        // Register this extension with Laravel.
        //
        Bundle::register($slug, $extension['bundles']);

        // Start the extension.
        //
        Bundle::start($slug);

        // Register this extension routes.
        //
        if ($routes = array_get($extension, 'routes'))
        {
            // Check if we've been given a closure.
            //
            if ( ! $routes instanceof Closure)
            {
                throw new Exception(Lang::line('extensions.invalid_routes', array('extension' => $slug))->get());
            }

            // Register it.
            //
            $routes();
        }

        // Register this extension listeners.
        //
        if ($listeners = array_get($extension, 'listeners'))
        {
            // Check if we've been given a closure.
            //
            if ( ! $listeners instanceof Closure)
            {
                throw new Exception(Lang::line('extensions.invalid_listeners', array('extension' => $slug))->get());
            }

            // Register it.
            //
            $listeners();
        }

        // The extension has been started.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: all()
     * --------------------------------------------------------------------------
     *
     * Returns all the extensions, both installed and uninstalled.
     *
     * @access   public
     * @return   array
     */
    public function all()
    {
        // Check if we have the extensions loaded.
        //
        if ( ! empty($this->extensions))
        {
            return $this->extensions;
        }

        // Initiate an empty array.
        //
        $extensions = array();

        // Get the installed extensions.
        //
        $this->installed = $this->installed();

        // Spin through the extensions from the directories, and merge with the installed extensions data.
        //
        foreach ($this->extensions_directories() as $extension)
        {
            // Get this extension slug.
            //
            $slug = $extension['info']['slug'];

            // Check if this extension is installed.
            //
            if ($info = array_get($this->installed, $slug))
            {
                // Update the extension information.
                //
                $extension['info']['installed']     = true;
                $extension['info']['version']       = ( $this->has_update($slug) ? $this->new_version($slug) : $this->current_version($slug));
                $extension['info']['enabled']       = $this->is_enabled($slug);
                $extension['info']['can_uninstall'] = $this->can_uninstall($slug);
                $extension['info']['has_update']    = $this->has_update($slug);

                // Check if the extension is enabled.
                //
                if ($this->is_enabled($slug))
                {
                    // Can we disable it ?
                    //
                    $extension['info']['can_disable'] = $this->can_disable($slug);
                }

                // The extension is disabled.
                //
                else
                {
                    // Can we enable it ?
                    //
                    $extension['info']['can_enable'] = $this->can_enable($slug);
                }

                // Sort the info array.
                //
                ksort($extension['info']);

                // Update the installed array.
                //
                $this->installed[ $slug ] = array_replace_recursive($extension, $this->installed[ $slug ]);
            }

            // The extension is uninstalled.
            //
            else
            {
                // Update the extension information.
                //
                $extension['info']['can_install'] = $this->can_install($slug);

                // Sort the info array.
                //
                ksort($extension['info']);

                // Store it in the array.
                //
                $this->uninstalled[ $slug ] = $extension;
            }

            // Store in the array.
            //
            $extensions[ $slug ] = $extension;
        }

        // Sort the extensions.
        //
        ksort($extensions);

        // Store and return the extensions.
        //
        return $this->extensions = $extensions;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: installed()
     * --------------------------------------------------------------------------
     *
     * Returns all the installed extensions, both enabled and disabled.
     *
     * @access   public
     * @return   array
     */
    public function installed($condition = null)
    {
        // Initiate an empty array.
        //
        $extensions = array();

        // Loop through the extensions.
        //
        foreach (Extension::all($condition) as $extension)
        {
            // Extension slug.
            //
            $slug = $extension->slug;

            // Extension information.
            //
            $extensions[ $slug ]['info'] = array(
                'enabled' => (bool) $extension->enabled,
                'slug'    => $slug,
                'version' => $extension->version
            );
        }

        // Sort the extensions.
        //
        ksort($extensions);

        // Store and return the installed extensions.
        //
        return $extensions;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: uninstalled()
     * --------------------------------------------------------------------------
     *
     * Returns all the uninstalled extensions
     *
     * @access   public
     * @return   array
     */
    public function uninstalled()
    {
        // Check if we have loaded the extensions already.
        //
        if ( ! empty($this->uninstalled))
        {
            return $this->uninstalled;
        }

        // Initiate an empty array.
        //
        $extensions = array();

        // Loop through the extensions.
        //
        foreach ($this->extensions as $extension)
        {
            // Extension slug.
            //
            $slug = $extension['info']['slug'];

            // Check if this extension is not installed.
            //
            if ($this->is_uninstalled($slug))
            {
                // extension information.
                //
                $extensions[ $slug ] = $extension;
            }
        }

        // Sort the extensions.
        //
        ksort($extensions);

        // Store and return the extensions.
        //
        return $this->uninstalled = $extensions;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: enabled()
     * --------------------------------------------------------------------------
     *
     * Returns all the enabled extensions
     *
     * @access   public
     * @return   array
     */
    public function enabled()
    {
        // If we already have the enabled extensions.
        //
        if ( ! empty($this->enabled))
        {
            return $this->enabled;
        }

        // Get the enabled extensions.
        //
        return $this->enabled = $this->installed(function($query){
            return $query->where('enabled', '=', '1');
        });
    }


    /**
     * --------------------------------------------------------------------------
     * Function: disabled()
     * --------------------------------------------------------------------------
     *
     * Returns all the disabled extensions
     *
     * @access   public
     * @return   array
     */
    public function disabled()
    {
        // If we already have the disabled extensions.
        //
        if ( ! empty($this->disabled))
        {
            return $this->disabled;
        }

        // Get the disabled extensions.
        //
        return $this->disabled = $this->installed(function($query){
            return $query->where('enabled', '=', '0');
        });
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_installed()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension is installed.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function is_installed($slug = null)
    {
        return (bool) array_get($this->installed, $slug);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_uninstalled()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension is uninstalled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function is_uninstalled($slug = null)
    {
        return (bool) ! array_get($this->installed, $slug);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_enabled()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension is enabled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function is_enabled($slug = null)
    {
        return (bool) array_get($this->installed, $slug . '.info.enabled');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_disabled()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension is disabled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function is_disabled($slug = null)
    {
        return (bool) ! array_get($this->installed, $slug . '.info.enabled');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: is_core()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension is a core extension.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function is_core($slug = null)
    {
        return (bool) array_get($this->extensions, $slug . '.info.is_core');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: exists()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension exists.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function exists($slug)
    {
        return (bool) array_get($this->extensions, $slug);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: can_install()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension can be installed.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function can_install($slug = null)
    {
        // If we are installing Platform, this check is useless.
        //
        if ($this->checking === false)
        {
            // Extension can be installed.
            //
            return true;
        }

        // Check if this extension exists.
        //
        if ( ! $this->exists($slug))
        {
            return false;
        }

        // Check if this extension has dependencies.
        //
        if ($dependencies = $this->has_dependencies($slug))
        {
            // Loop through the dependencies.
            //
            foreach ($dependencies as $dependent)
            {
                // Check if this dependent extension is uninstalled and/or disabled.
                //
                if ($this->is_uninstalled($dependent) or $this->is_disabled($dependent))
                {
                    // Extension can't be installed.
                    //
                    return false;
                }
            }
        }

        // Extension can be installed.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: can_uninstall()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension can be uninstalled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function can_uninstall($slug = null)
    {
        // First check if this is a core extension.
        //
        if (array_get($this->extensions, $slug . '.info.is_core'))
        {
            // Core extensions can't be uninstalled !
            //
            return false;
        }

        // Now check if this extension is installed.
        //
        if ( ! $this->is_installed($slug))
        {
            return false;
        }

        // Check if this extension has dependents.
        //
        if ($dependents = array_get($this->dependents, $slug))
        {
            // Loop through the dependents.
            //
            foreach ($dependents as $dependent)
            {
                // Check if this dependent extension is installed.
                //
                if ($this->is_installed($dependent))
                {
                    return false;
                }
            }
        }

        // Extension can be uninstalled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: can_enable()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension can be enabled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function can_enable($slug = null)
    {
        // If we are installing Platform, this check is useless.
        //
        if ($this->checking === false)
        {
            // Extension can be enabled.
            //
            return true;
        }

        // Check if this extension is already enabled.
        //
        if($this->is_enabled($slug))
        {
            return false;
        }

        // Check if this extension has dependencies.
        //
        if ($dependencies = array_get($this->dependencies, $slug))
        {
            // Loop through the dependencies.
            //
            foreach ($dependencies as $dependent)
            {
                // Check if this dependent extension is enabled and installed.
                //
                if ($this->is_uninstalled($dependent))
                {
                    return false;
                }
            }
        }

        // Extension can be enabled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: can_disable()
     * --------------------------------------------------------------------------
     *
     * Checks if an extension can be disabled.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function can_disable($slug = null)
    {
        // First check if this is a core extension.
        //
        if (array_get($this->extensions, $slug . '.info.is_core'))
        {
            // Core extensions can't be disabled !
            //
            return false;
        }

        // Now check if this extension is enabled.
        //
        if ( ! $this->is_enabled($slug))
        {
            // Extension can't be disabled.
            //
            return false;
        }

        // Check if this extension has dependents.
        //
        if ($dependents = array_get($this->dependents, $slug))
        {
            // Loop through the dependents.
            //
            foreach ($dependents as $dependent)
            {
                // Check if this dependent extension is enabled.
                //
                if ($this->is_installed($dependent))
                {
                    // Extension can't be disabled.
                    //
                    return false;
                }
            }
        }

        // Extension can be disabled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: has_update()
     * --------------------------------------------------------------------------
     *
     * Checks if a extension has an update available.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function has_update($slug = null)
    {
        // Check if this extension is installed.
        //
        if ( $this->is_installed($slug))
        {
            // Get the info from the extension.php file.
            //
            $extension = $this->get($slug);

            // Compare both versions, and return the result.
            //
            return ( version_compare($extension['info']['version'], $this->installed[ $slug ]['info']['version']) > 0 );
        }

        // The extension is not installed.
        //
        return false;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: has_dependencies()
     * --------------------------------------------------------------------------
     *
     * Checks if a extension has dependencies, and return them if found any.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function has_dependencies($slug = null)
    {
        return ( array_get($this->dependencies, $slug) ?: false );
    }


    /**
     * --------------------------------------------------------------------------
     * Function: has_dependents()
     * --------------------------------------------------------------------------
     *
     * Checks if a extension has dependents, and return them if found any.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function has_dependents($slug = null)
    {
        return ( array_get($this->dependents, $slug) ?: false );
    }


    /**
     * --------------------------------------------------------------------------
     * Function: required_extensions()
     * --------------------------------------------------------------------------
     *
     * This returns all the required extensions that a extension needs to be installed.
     *
     * It checks if the dependent extensions are: ' Uninstalled or Disabled '
     *
     * If one of the checks returns TRUE, it means that the extension can't be installed.
     *
     * @access   public
     * @param    string
     * @return   array
     */
    public function required_extensions($slug = null)
    {
        // Get this extension dependencies.
        //
        if ( ! $dependencies = array_get($this->dependencies, $slug))
        {
            return array();
        }

        // Initiate an empty array.
        //
        $required = array();

        // Spin through this extension dependencies.
        //
        foreach ($dependencies as $dependent)
        {
            // Check if this dependent extensions is not installed or is disabled.
            //
            if ($this->is_uninstalled($dependent) or $this->is_disabled($dependent))
            {
                $required[ $dependent ] = $dependent;
            }
        }

        // Return the required extensions.
        //
        return $required;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: current_version()
     * --------------------------------------------------------------------------
     *
     * Returns the current version of a extension.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function current_version($slug = null)
    {
        return array_get($this->installed, $slug . '.info.version');
    }


    /**
     * --------------------------------------------------------------------------
     * Function: new_version()
     * --------------------------------------------------------------------------
     *
     * Returns the new version of a extension, if an update is available, otherwise,
     * returns the current version of the extension.
     *
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function new_version($slug = null)
    {
        // Check if this extension has an update available.
        //
        if ($this->has_update($slug))
        {
            return array_get($this->extensions, $slug . '.info.version');
        }

        // No update available, return the current version.
        //
        return $this->current_version($slug);
    }
    

    /**
     * --------------------------------------------------------------------------
     * Function: install()
     * --------------------------------------------------------------------------
     *
     * Installs a extension by the given slug.
     *
     * As an optional parameter, you can also enable the extension automatically.
     *
     * @access   public
     * @param    string
     * @param    boolean
     * @return   boolean
     */
    public function install($slug = null, $enable = false)
    {
        // Check if this extension is already installed.
        //
        if (Extension::find($slug))
        {
            throw new Exception(Lang::line('extensions.install.fail', array('extension' => $slug))->get());
        }

        // Check if this extension can be installed.
        //
        if ( ! $this->can_install($slug))
        {
            throw new Exception(Lang::line('extensions.install.fail', array('extension' => $slug))->get());
        }

        // Get this extension information.
        //
        $extension = $this->get($slug);

        // Create a new model instance.
        //
        $model = new Extension(array(
            'slug'    => $extension['info']['slug'],
            'version' => $extension['info']['version'],
            'enabled' => (int) ( $is_core = $extension['info']['is_core'] ? 1 : $enable)
        ));
        $model->save();

        // Start the extension.
        //
        $this->start($slug);

        // Resolves core tasks.
        //
        require_once path('sys') . 'cli/dependencies' . EXT;

        // Run this extension migrations.
        //
        Command::run(array('migrate', array_get($extension, 'bundles.handles', $slug)));

        // Disable menus related to this extension, if the extension is disabled by default.
        //
        if ( ! $is_core and ! $enable)
        {
            try
            {
                $menus = API::get('menus/flat', array('extension' => $slug));
                foreach ($menus as $menu)
                {
                    API::put('menus/' . $menu['slug'], array('status' => 0));
                }
            }
            catch (APIClientException $e)
            {

            }
        }

        // extension installed.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: uninstall()
     * --------------------------------------------------------------------------
     *
     * Uninstalls a extension by the given slug.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function uninstall($slug = null)
    {
        // Get this extension information from the database.
        //
        if (is_null($extension = Extension::find($slug)))
        {
            throw new Exception(Lang::line('extensions.not_found', array('extension' => $slug))->get());
        }

        // Check if this extension can be uninstalled.
        //
        if ( ! $this->can_uninstall($slug))
        {
            throw new Exception(Lang::line('extensions.uninstall.fail', array('extension' => $slug))->get());
        }

        // Resolves core tasks.
        //
        require_once path('sys') . 'cli/dependencies' . EXT;

        // Start the extension so we can find it's bundle path.
        //
        $this->start($slug);

        // Get the migrations of this extension that were executed.
        //
        $migrations = DB::table('laravel_migrations')->where('bundle', '=', $slug)->order_by('name', 'DESC')->get();

        // Loop through the installed migrations.
        //
        foreach ($migrations as $migration)
        {
            // Include the migration file.
            //
            require_once Bundle::path($slug) . 'migrations' . DS . $migration->name . EXT;

            // Prepare the class name.
            //
            $class = Bundle::class_prefix($slug) . \Laravel\Str::classify( substr( $migration->name, 18 ) );

            // Initiate the migration class.
            //
            $migration = new $class;

            // Run down the migration.
            //
            $migration->down();
        }

        // Remove the entry from the migrations table.
        //
        DB::table('laravel_migrations')->where('bundle', '=', $slug)->delete();

        // Delete the extension reference from the database.
        //
        $extension->delete();

        // extension uninstalled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: enable()
     * --------------------------------------------------------------------------
     *
     * Enables a extension by the given slug.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function enable($slug = null)
    {
        // Get this extension information.
        //
        if (is_null($extension = Extension::find($slug)))
        {
            throw new Exception(Lang::line('extensions.not_found', array('extension' => $slug))->get());
        }

        // Check if this extension can be enabled.
        //
        if ( ! $this->can_enable($slug))
        {
            throw new Exception(Lang::line('extensions.enable.fail', array('extension' => $slug))->get());
        }

        // Enable all menus related to this extension.
        //
        try
        {
            $menus = API::get('menus/flat', array('extension' => $slug));
            foreach ($menus as $menu)
            {
                API::put('menus/' . $menu['slug'], array('status' => 1));
            }
        }
        catch (APIClientException $e)
        {

        }

        // Enable the extension.
        //
        $extension->enabled = 1;
        $extension->save();

        // extension enabled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: disable()
     * --------------------------------------------------------------------------
     *
     * Disables a extension by the given slug.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function disable($slug = null)
    {
        // Get this extension information.
        //
        if (is_null($extension = Extension::find($slug)))
        {
            throw new Exception(Lang::line('extensions.not_found', array('extension' => $slug))->get());
        }

        // Check if this extension can be disabled.
        //
        if ( ! $this->can_disable($slug))
        {
            throw new Exception(Lang::line('extensions.disable.fail', array('extension' => $slug))->get());
        }

        // Disable all menus related to this extension.
        //
        try
        {
            $menus = API::get('menus/flat', array('extension' => $slug));
            foreach ($menus as $menu)
            {
                API::put('menus/' . $menu['slug'], array('status' => 0));
            }
        }
        catch (APIClientException $e)
        {}

        // Disable the extension.
        //
        $extension->enabled = 0;
        $extension->save();

        // extension disabled.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: update()
     * --------------------------------------------------------------------------
     *
     * Updates a extension by the given slug.
     *
     * @access   public
     * @param    string
     * @return   boolean
     */
    public function update($slug)
    {
        // Get this extension information.
        //
        if (is_null($extension = Extension::find($slug)))
        {
            throw new Exception(Lang::line('extensions.not_found', array('extension' => $slug))->get());
        }

        // Get this extension information.
        //
        $info = $this->get($slug);

        // Update extension.
        //
        $extension->version = $info['info']['version'];
        $extension->save();

        // Start the extension.
        //
        $this->start($slug);

        // Resolves core tasks.
        //
        require_once path('sys') . 'cli/dependencies' . EXT;

        // Run this extension migrations.
        //
        Command::run(array('migrate', $slug));

        // extension was updated.
        //
        return true;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get()
     * --------------------------------------------------------------------------
     *
     * Retrieve all the needed information about a extension.
     *
     * @access   public
     * @param    string
     * @return   array
     */
    public function get($slug = null)
    {
        // Check if the extension is already in the array.
        //
        if ($extension = array_get($this->extensions, $slug))
        {
            return $extension;
        }

        // Check if the extension.php file of this extension exists.
        //
        if ( ! $file = $this->find_extension($slug))
        {
            return false;
        }

        // Read the extension.php file.
        //
        $extension = require $file;

        // Some requirements for the extension.php file.
        //
        if ( ! is_array($extension) or ! array_get($extension, 'info.name') or ! array_get($extension, 'info.version'))
        {
            throw new Exception(Lang::line('extensions.invalid_file', array('extension' => $slug))->get());
        }

        // Add/change some extension information..
        //
        $extension['info']['slug']      = $slug;
        $extension['info']['is_core']   = (bool) ( array_get($extension, 'info.is_core') ?: false );
        $extension['info']['enabled']   = (bool) ( array_get($extension, 'info.enabled') ?: false );
        $extension['info']['installed'] = false;

        // Bundles array, so we can register the extension as a bundle in Laravel.
        //
        $extension['bundles'] = array(
            'handles'  => $slug,
            'location' => 'path: ' . dirname($file)
        );

        // Sort this extension info array.
        //
        ksort($extension['info']);

        // Check if this extension has dependencies.
        //
        if ($dependencies = array_get($extension, 'dependencies'))
        {
            foreach ($dependencies as $dependent)
            {
                $this->dependencies[ $slug ][] = $dependent;
                $this->dependents[ $dependent ][] = $slug;
            }
        }

        // Return the extension information.
        //
        return $extension;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: find_extension()
     * --------------------------------------------------------------------------
     *
     * Finds the extension.php file of a extension with the given slug.
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public function find_extension($slug = null)
    {
        // Check if we have a slug.
        //
        if (is_null($slug))
        {
            return false;
        }

        // We'll search for the extension in the root dir first.
        //
        $files = glob(path('extensions') . $slug . DS . 'extension' . EXT);

        // We couldn't find the extension in the first path, so we'll try the second path.
        //
        if (empty($files))
        {
            $files = glob(path('extensions') . '*' . DS . $slug . DS . 'extension' . EXT);
        }

        // Return the file path.
        //
        return ( ! empty($files) ? $files[0] : false );
    }


    /**
     * --------------------------------------------------------------------------
     * Function: extensions_directories()
     * --------------------------------------------------------------------------
     *
     * This returns all the extensions within the extensions directory.
     *
     * @access   private
     * @return   array
     */
    private function extensions_directories()
    {
        // Initiate an empty array.
        //
        $extensions = array();

        // Declare the directories we want to search for extensions.
        //
        $directories = array(
            (array) glob(path('extensions') . '*' . DS . '*' . DS . 'extension' . EXT, GLOB_NOSORT),
            (array) glob(path('extensions') . '*' . DS . 'extension' . EXT, GLOB_NOSORT)
        );

        // Loop through the directories.
        //
        foreach ($directories as $directory)
        {
            // Loop through each extension.
            //
            foreach ((array) $directory as $extension)
            {
                // The full path to this extension
                //
                $extension = dirname($extension);

                // The name of this extension.
                //
                $slug = basename($extension);

                // Check if the extension is not in the array already.
                //
                if ( ! in_array($slug, $extensions))
                {
                    $extensions[ $slug ] = $this->get($slug);
                }
            }
        }

        // Sort the extensions.
        //
        ksort($extensions);

        // Return and store the extensions.
        //
        return $this->extensions = $extensions;
    }


    /**
     * --------------------------------------------------------------------------
     * Function: prepare()
     * --------------------------------------------------------------------------
     *
     * Prepares the Platform database for extensions.
     *
     * @access   public
     * @return   void
     */
    public function prepare()
    {
        // Disable the checking.
        //
        $this->checking = false;

        // Resolves core tasks.
        //
        require_once path('sys') . 'cli/dependencies' . EXT;

        // Check for the migrations table.
        //
        try
        {
            DB::table('laravel_migrations')->count();
        }
        catch ( Exception $e )
        {
            Command::run(array('migrate:install'));
        }

        // Check for the extensions table.
        //
        try
        {
            DB::table('extensions')->count();
        }
        catch ( Exception $e )
        {
            Schema::create('extensions', function($table){
                $table->increments('id');
                $table->string('slug', 50)->unique();
                $table->string('version', 10)->default(0);
                $table->boolean('enabled');
                $table->timestamps();
            });
        }

        // Start the extensions, just in case the install process got interrupted.
        //
        $this->start_extensions();
    }
}
