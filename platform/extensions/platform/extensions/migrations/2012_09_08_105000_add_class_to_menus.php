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

class Extensions_Add_Class_To_Menus
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
		$extensions = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-extensions')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($extensions)
		{
			$extensions->class = 'icon-leaf';
			$extensions->save();
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
		$extensions = Menu::find(function($query) use ($admin_tree)
		{
			return $query->where('slug', '=', 'admin-extensions')
			             ->where(Menu::nesty_col('tree'), '=', $admin_tree);
		});

		if ($extensions)
		{
			$extensions->class = '';
			$extensions->save();
		}
	}

}
