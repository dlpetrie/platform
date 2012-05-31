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
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Platform\Menus\Widgets;

use API;
use Input;
use Theme;

class Menus
{

	/**
	 * Returns the admin navigation for Platform.
	 * Currently the main nav is limited to 1 level
	 * of depth.
	 *
	 * @return  View
	 */
	public function primary()
	{
		// Get menu items
		$items = API::get('menus/admin_menu', array('depth' => 0));

		return Theme::make('menus::widgets.primary')
		            ->with('items', $items);
	}

	public function secondary()
	{
		// Get secondary navigation
		$items = API::get('menus/children', array(
			'id'    => Input::get('id'),
			'slug'  => Input::get('slug'),
			'depth' => 0,
		));

echo '<pre>';print_r(Input::get());print_r($items);die();

		return Theme::make('menus::widgets.secondary')
		            ->with('items', $items);
	}

}
