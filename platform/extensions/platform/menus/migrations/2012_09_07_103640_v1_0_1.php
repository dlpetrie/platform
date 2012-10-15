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
use Platform\Menus\Menu;


/**
 * --------------------------------------------------------------------------
 * Install Class v1.0.1
 * --------------------------------------------------------------------------
 * 
 * Adds a class column to menus.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 */
class Menus_v1_0_1
{
    /**
     * --------------------------------------------------------------------------
     * Function: up()
     * --------------------------------------------------------------------------
     *
     * Make changes to the database.
     *
     * @access   public
     * @return   void
     */
	public function up()
	{
        /*
         * --------------------------------------------------------------------------
         * # 1) Update the menu table.
         * --------------------------------------------------------------------------
         */
		Schema::table('menus', function($table)
		{
			$table->string('class')->nullable();
		});


        /*
         * --------------------------------------------------------------------------
         * # 2) Update the menu items.
         * --------------------------------------------------------------------------
         */
		// Get the admin menu.
		//
		$admin_menu    = Menu::admin_menu();
		$admin_menu_id = $admin_menu->{Menu::nesty_col('tree')};

		// Update the system class.
		//
		$system = Menu::find(function($query) use ($admin_menu_id)
		{
			return $query->where('slug', '=', 'admin-system')
			             ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
		});

		if ($system)
		{
			$system->class = 'icon-cog';
			$system->save();
		}

		// Update the menus class.
		//
		$menus = Menu::find(function($query) use ($admin_menu_id)
		{
			return $query->where('slug', '=', 'admin-menus')
			             ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
		});

		if ($menus)
		{
			$menus->class = 'icon-th-list';
			$menus->save();
		}
	}


    /**
     * --------------------------------------------------------------------------
     * Function: down()
     * --------------------------------------------------------------------------
     *
     * Revert the changes to the database.
     *
     * @access   public
     * @return   void
     */
	public function down()
	{
        /*
         * --------------------------------------------------------------------------
         * # 1) Update the menu table.
         * --------------------------------------------------------------------------
         */
		Schema::table('menus', function($table)
		{
			$table->drop_column('class');
		});


        /*
         * --------------------------------------------------------------------------
         * # 2) Update the menu items.
         * --------------------------------------------------------------------------
         */
		// Get the admin menu.
		//
		$admin_menu    = Menu::admin_menu();
		$admin_menu_id = $admin_menu->{Menu::nesty_col('tree')};

		// Update the system class.
		//
		$system = Menu::find(function($query) use ($admin_menu_id)
		{
			return $query->where('slug', '=', 'admin-system')
			             ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
		});

		if ($system)
		{
			$system->class = '';
			$system->save();
		}

		// Update the menus class.
		//
		$menus = Menu::find(function($query) use ($admin_menu_id)
		{
			return $query->where('slug', '=', 'admin-menus')
			             ->where(Menu::nesty_col('tree'), '=', $admin_menu_id);
		});

		if ($menus)
		{
			$menus->class = '';
			$menus->save();
		}
	}
}

/* End of file 2012_09_07_103640_add_class_column.php */
/* Location: ./platform/extensions/platform/menus/migrations/2012_09_07_103640_add_class_column.php */