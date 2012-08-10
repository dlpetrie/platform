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

use Platform\Menus\Menu;

class Menus_API_Menus_Controller extends API_Controller
{

	/**
	 * Returns an array of menus or a given
	 * menu, should the slug be provided.
	 *
	 *	<code>
	 *		$menus = API::get('menus');
	 *		$admin = API::get('menus/admin');
	 *	</code>
	 *
	 * @param   string  $slug
	 * @return  array
	 */
	public function get_index($slug = false)
	{
		// All menus
		if ($slug == false)
		{
			// Get menus
			$menus = Menu::menus();

			foreach ($menus as $menu)
			{
				// Adding children?
				if (Input::get('children'))
				{
					$menu->children();
				}
				else
				{
					unset($menu->children);
				}
			}

			return new Response($menus);
		}

		if (($menu = Menu::find_root($slug)) === null)
		{
			return new Response(array(
				'message' => "Menu [$slug] either not a root menu item or doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		// Adding children?
		if (Input::get('children'))
		{
			$menu->children(Input::get('limit', false));
		}
		else
		{
			unset($menu->children);
		}

		return new Response($menu);
	}

	/**
	 * Creates a menu with the given properties.
	 *
	 *	<code>
	 *		$menu = API::post('menus', $data);
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_index()
	{
		// Check for valid data
		if (($children = Input::get('children')) === null or ! is_array($children) or count($children) === 0)
		{
			return new Response(array(
				'message' => "Invalid children provided to create Menu [$slug]. ",
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}

		$name = Input::get('name');
		$slug = Input::get('slug');

		try
		{
			$menu = Menu::from_hierarchy_array(false, $children, function($root_item) use ($name, $slug)
			{
				// Add values
				$root_item->name = $name;
				$root_item->slug = $slug;

				// All new created items are user editable
				$root_item->user_editable = 1;

				return $root_item;
			});
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new Response($menu);
	}

	/**
	 * Updates a menu with the given properties.
	 *
	 *	<code>
	 *		$menu = API::put('menus/admin', $data);
	 *	</code>
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function put_index($slug)
	{
		// Validate the menu is a root item
		if (($menu = Menu::find_root($slug)) === null)
		{
			return new Response(array(
				'message' => "Menu [$slug] either not a root menu item or doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		// Check for valid data
		if (($children = Input::get('children')) === null or ! is_array($children))
		{
			return new Response(array(
				'message' => "Invalid children provided for Menu [$slug]. To remove children, pass an empty array through instead.",
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}

		$name = Input::get('name', $menu['name']);
		$slug = Input::get('slug', $slug);

		try
		{
			$menu = Menu::from_hierarchy_array($menu['id'], $children, function($root_item) use ($name, $slug)
			{
				if ($name and ($root_item->user_editable))
				{
					$root_item->name = $name;
				}

				if ($slug and ($root_item->user_editable))
				{
					$root_item->slug = $slug;
				}

				return $root_item;
			});

			// Load in children
			$menu->children();
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}

		return new Response($menu);
	}

	/**
	 * Returns a (recursive) array of menu
	 * children for a given menu slug.
	 *
	 *	<code>
	 *		$admin_children = API::get('menus/admin/children');
	 *	</code>
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function get_children($slug)
	{
		// Find the children belonging to either a root item
		// or a child item
		if (($menu = Menu::find_root($slug)) === null and ($menu = Menu::find($slug)) === null)
		{
			return new Response(array(
				'message' => "Menu [$slug] either not a root menu item or doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		$children = $menu->children(Input::get('limit', false));

		// If the person wants to filter by the type
		if (($filter_type = Input::get('filter_type')) !== null)
		{
			// If they're asking for a particular filter
			// type. The range() matches the constants
			// found in Platform\Menus\Menu. Alernatively,
			// a string 'automatic' can be passed through in
			// which case we'll use Session data to filter types.
			if ((is_numeric($filter_type) and in_array((int) $filter_type, range(0, 3))) or $filter_type === 'automatic')
			{
				$this->filter_children_recursively($children, $filter_type);
			}
		}

		return new Response($children);
	}

	/**
	 * Returns a flat array of menu children.
	 *
	 *	<code>
	 *		$all_menu_children_flat = API::get('menus/flat');
	 *		$admin_children_flat    = API::get('menus/admin/children/flat');
	 *	</code>
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function get_flat($slug = false)
	{
		if ($slug == false)
		{
			$menus = Menu::all();

			// Unset the children array as it's
			// irrelevent with flat children
			foreach ($menus as $menu)
			{
				unset($menu->children);
			}

			return new Response($menus);
		}

		// Find the children belonging to either a root item
		// or a child item
		if (($menu = Menu::find_root($slug)) === null and ($menu = Menu::find($slug)) === null)
		{
			return new Response(array(
				'message' => "Menu [$slug] either not a root menu item or doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		// Find all children that are between the menu's properties
		$children = Menu::all(function($query) use ($menu)
		{
			return $query->where(Menu::nesty_col('left'), 'BETWEEN', DB::raw('\''.($menu->{Menu::nesty_col('left')} + 1).'\' AND \''.$menu->{Menu::nestY_col('right')}.'\''));
		});

		foreach ($children as $item)
		{
			unset($item->children);
		}

		return new Response($children);
	}

	/**
	 * Returns the children of a menu with the
	 * given item ID.
	 *
	 *	<code>
	 *		API::get('menus/children', array(
	 *			'id'    => 5,
	 *			'depth' => 2,
	 *		));
	 *	</code>
	 *
	 * @return  array
	 */
	public function geat_children()
	{
		if ($id = Input::get('id'))
		{
			$parent = Menu::find(Input::get('id'));
		}
		elseif ($slug = Input::get('slug'))
		{
			$parent = Menu::find(function($query) use ($slug)
			{
				return $query->where('slug', '=', $slug);
			});
		}
		else
		{
			return array(
				'status'  => false,
				'message' => 'Either a parent ID or slug is required to retrieve it\'s children.',
			);
		}

		// Invalid ID bafor parent
		// Nesty
		if ($parent === null)
		{
			return array(
				'status'  => false,
				'message' => "The parent Nesty model ID [$id] is invalid",
			);
		}

		if ( ! $children = $parent->enabled_children(Input::get('depth', 0)))
		{
			return array(
				'status'  => false,
				'message' => 'There are no children for the given menu.',
			);
		}

		// filter out children children based on type
		$menu_children = array();
		foreach($children as $child)
		{
			switch ($child['type'])
			{
				case 0: // always show
					$menu_children[] = $child;
				break;
				case 1: // show if logged in
					if (Sentry::check())
					{
						$menu_children[] = $child;
					}
				break;
				case 2: // show if logged out
					if ( ! Sentry::check())
					{
						$menu_children[] = $child;
					}
				break;
				case 3: // show if admin
					if (Sentry::check() and Sentry::user()->has_access(array('is_admin', 'superuser')))
					{
						$child['uri'] = ADMIN.'/'.$child['uri'];
						$menu_children[] = $child;
					}
				break;
			}
		}

		return array(
			'status'   => true,
			'children' => $menu_children,
		);
	}

	/**
	 * Sets the active menu in the Menu instance.
	 *
	 * @return  array
	 */
	public function post_active()
	{
		$active = Input::get('slug', Input::get('id'));

		if (Menu::active($active) === false)
		{
			return array(
				'status'  => false,
				'message' => "The active menu [$active] doesn't exist.",
			);
		}

		return array('status' => true);
	}

	/**
	 * Gets the active menu in the Menu instance.
	 *
	 * @return  array
	 */
	public function get_active()
	{
		if ( ! $active = Menu::active())
		{
			return new Response(array(
				'message' => 'No active menu set.',
			), API::STATUS_NOT_FOUND);
		}

		return new Response($active);
	}

	/**
	 * Returns the active path
	 *
	 * @return  array
	 */
	public function get_active_path()
	{
		if ( ! $active = Menu::active())
		{
			return new Response(array(
				'message' => 'No active menu set.',
			), API::STATUS_NOT_FOUND);
		}

		return new Response(Menu::active_path());
	}

	/**
	 * Enables menus with given filters.
	 *
	 * @return  array
	 */
	public function post_enable()
	{
		// Enabling an ID
		if ($id = Input::get('id'))
		{
			$menu = Menu::find($id);

			if ($menu !== null)
			{
				$menu->status = 1;
				$menu->save();

				return array('status' => true);
			}
		}

		// Enabling by slug
		elseif ($slug = Input::get('slug'))
		{
			$menu = Menu::find(function($query) use ($slug)
			{
				return $query->where('slug', '=', $slug);
			});

			if ($menu !== null)
			{
				$menu->status = 1;
				$menu->save();

				return array('status' => true);
			}
		}

		// Enabling by extension
		if ($extension = Input::get('extension'))
		{
			$menus = Menu::all(function($query) use ($extension)
			{
				return $query->where('extension', '=', $extension);
			});

			if ( ! empty($menus))
			{
				foreach ($menus as $menu)
				{
					$menu->status = 1;
					$menu->save();
				}

				return array('status'  => true);
			}
		}

		// Failure
		return array(
			'status'  => false,
			'message' => 'Could\'t find menu to enable.',
		);
	}

	/**
	 * Disables menus with given filters.
	 *
	 * @return  array
	 */
	public function post_disable()
	{
		// Disabling an ID
		if ($id = Input::get('id'))
		{
			$menu = Menu::find($id);

			if ($menu !== null)
			{
				$menu->status = 0;
				$menu->save();

				return array('status' => true);
			}
		}

		// Disabling by slug
		elseif ($slug = Input::get('slug'))
		{
			$menu = Menu::find(function($query) use ($slug)
			{
				return $query->where('slug', '=', $slug);
			});

			if ($menu !== null)
			{
				$menu->status = 0;
				$menu->save();

				return array('status' => true);
			}
		}

		// Disabling by extension
		if ($extension = Input::get('extension'))
		{
			$menus = Menu::all(function($query) use ($extension)
			{
				return $query->where('extension', '=', $extension);
			});

			if ( ! empty($menus))
			{
				foreach ($menus as $menu)
				{
					$menu->status = 0;
					$menu->save();
				}

				return array('status'  => true);
			}
		}

		// Failure
		return array(
			'status'  => false,
			'message' => 'Could\'t find menu to disable.',
		);
	}

	/**
	 * Deletes a menu.
	 *
	 *	<code>
	 *		API::delete('menus/admin');
	 *	</code>
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function delete_index($slug)
	{
		// Validate the menu is a root item
		if (($menu = Menu::find_root($slug)) === null)
		{
			return new Response(array(
				'message' => "Menu [$slug] either not a root menu item or doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		// Delete
		$menu->delete();

		return new Response(null, API::STATUS_NO_CONTENT);
	}

	/**
	 * Recursively filters menu items.
	 *
	 * @param   array   $children
	 * @param   string  $type
	 * @return  void
	 */
	protected function filter_children_recursively(array &$children, $type)
	{
		foreach ($children as $index => &$child)
		{
			if ($type == 'automatic')
			{
				// Do a switch based on the menu item
				// type
				switch ($child->type)
				{
					// Remove from anyone who's not logged in
					case Menu::TYPE_LOGGED_IN:
						if ( ! Sentry::check())
						{
							array_splice($children, $index, 1);
						}
						break;

					// Remove from anyone who's logged in
					case Menu::TYPE_LOGGED_OUT:
						if (Sentry::check())
						{
							array_splice($children, $index, 1);
						}
						break;

					// Remove from anyone who's not admin
					case Menu::TYPE_ADMIN:
						if (Sentry::check() and Sentry::user()->has_access(array('is_admin', 'superuser')))
						{
							$child['uri'] = ADMIN.'/'.$child['uri'];
						}
						else
						{
							array_splice($children, $index, 1);
						}
						break;
				}
			}

			// Do a switch based on the filter type requested
			elseif (in_array($type, range(0, 3)))
			{
				// Check the type matches the requested typ
				if ($child->type != $type)
				{
					array_splice($children, $index, 1);
				}
			}

			// Recursive baby!
			if (count($child->children) > 0)
			{
				$this->filter_children_recursively($child->children, $type);
			}
		}
	}

}
