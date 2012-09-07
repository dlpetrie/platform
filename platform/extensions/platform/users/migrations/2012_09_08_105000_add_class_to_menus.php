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

use Platform\Menus\Menu;

class Users_Add_Class_To_Menus
{

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		/* # Update Menu Items
		================================================== */

		// Get hte admin menu
		$admin      = Menu::admin_menu();
		$admin_tree = $admin->{Menu::nesty_col('tree')};

		// Update groups list link
		$users = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-users')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($users)
		{
			$users->class = 'icon-user';
			$users->save();
		}

		// Update users list link
		$users_list = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-users-list')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($users_list)
		{
			$users_list->class = 'icon-user';
			$users_list->save();
		}

		// Update groups list link
		$groups_list = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-groups-list')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($groups_list)
		{
			$groups_list->class = 'icon-user';
			$groups_list->save();
		}
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// Get hte admin menu
		$admin      = Menu::admin_menu();
		$admin_tree = $admin->{Menu::nesty_col('tree')};

		// Update groups list link
		$users = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-users')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($users)
		{
			$users->class = '';
			$users->save();
		}

		// Update users list link
		$users_list = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-users-list')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($users_list)
		{
			$users_list->class = '';
			$users_list->save();
		}

		// Update groups list link
		$groups_list = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-groups-list')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($groups_list)
		{
			$groups_list->class = '';
			$groups_list->save();
		}
	}

}
