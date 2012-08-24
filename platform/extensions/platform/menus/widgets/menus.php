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
use APIClientException;
use Input;
use Sentry;
use Theme;

class Menus
{

	/**
	 * Returns a navigation menu, based off the active menu.
	 *
	 * If the start is an integer, it's the depth from the
	 * top level item based on the current active item. If it's
	 * a string, it's the slug of the item to start rendering
	 * from, irrespective of active item.
	 *
	 * @param   int     $start
	 * @param   int     $children_depth
	 * @param   string  $class
	 * @param   string  $before_uri
	 * @param   string  $class
	 */
	public function nav($start = 0, $children_depth = 0, $class = null, $before_uri = null)
	{
		// We have the slug?
		if ( ! is_numeric($start))
		{
			// Make sure we have a slug
			if ( ! strlen($start))
			{
				return '';
			}

			try
			{
				$items = API::get('menus/'.$start.'/children', array(

					// Only enabled
					'enabled' => true,

					// Pass through the children depth
					'limit' => $children_depth ?: false,

					// We want to automatically filter
					// what items show (according to Session)
					// data
					'filter_visibility' => 'automatic',
				));
			}
			catch (APIClientException $e)
			{
				return '';
			}
		}

		try
		{
			$active_path = API::get('menus/active_path');
		}
		catch (APIClientException $e)
		{
			// Empty active path
			$active_path = array();
		}

		// Le'ts get menus according to the
		// start depth and what is the active menu.
		if (is_numeric($start))
		{
			// Check the start depth exists
			if ( ! isset($active_path[(int) $start]))
			{
				return '';
			}

			// Items
			try
			{
				$items = API::get('menus/'.$active_path[(int) $start].'/children', array(
					'limit' => $children_depth ?: false,
				));
			}
			catch (APIClientException $e)
			{
				return '';
			}
		}

		// Return the widget
		return Theme::make('menus::widgets.nav')
		            ->with('items', $items)
		            ->with('active_path', $active_path)
		            ->with('class', $class)
		            ->with('before_uri', $before_uri);
	}

}
