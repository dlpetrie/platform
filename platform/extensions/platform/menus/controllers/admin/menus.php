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

class Menus_Admin_Menus_Controller extends Admin_Controller
{
	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-menus');
	}

	public function get_index()
	{
		try
		{
			$menus = API::get('menus');
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to(ADMIN);
		}

		return Theme::make('menus::index')
		            ->with('menus', $menus);
	}

	public function get_create()
	{
		return $this->get_edit();
	}

	/**
	 * Returns the edit / create view for
	 * a menu.
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function get_edit($slug = false)
	{
		// If we are editing a menu
		if ($slug != false)
		{
			try
			{
				$menu = API::get('menus/'.$slug, array(
					'children' => true,
				));
			}
			catch (APIClientException $e)
			{
				Platform::messages()->error($e->getMessage());

				foreach ($e->errors() as $error)
				{
					Platform::messages()->error($error);
				}

				return Redirect::to_secure(ADMIN.'/menus');
			}
		}
		else
		{
			// Fallback array
			$menu = array();
		}

		// Get all items
		try
		{
			$all_items = API::get('menus/flat');
		}
		catch (APIClientException $e)
		{
			$all_items = array();
		}

		// Get the last item's ID
		$last_item_id = array_get(end($all_items), 'id', 0);

		// Get array of persisted menu slugs. It's used
		// by javascript to validate unique slugs on
		// client end in addition to server end.
		$persisted_slugs = array();
		foreach ($all_items as $item)
		{
			$persisted_slugs[] = array_get($item, 'slug');
		}

		// Return the edit view
		return Theme::make('menus::edit')
		            ->with('menu', $menu)
		            ->with('menu_slug', array_get($menu, 'slug', false))
		            ->with('item_template', json_encode(Theme::make('menus::edit/item_template')->render()))
		            ->with('last_item_id', $last_item_id)
		            ->with('root_slug', isset($menu['slug']) ? $menu['slug'] : null)
		            ->with('persisted_slugs', json_encode($persisted_slugs));
	}

	/**
	 * Processes editing / creating a menu.
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function post_edit($slug = false)
	{
		$input_hierarchy = Input::get('items_hierarchy');

		// JSON string on non-AJAX form
		if (is_string($input_hierarchy))
		{
			$input_hierarchy = json_decode($input_hierarchy, true);
		}

		// Check for input hierarchy
		if ( ! $input_hierarchy or ! is_array($input_hierarchy))
		{
			if (Request::ajax())
			{
				return new Response(array(
					'message' => 'No items hierarchy was provided.'
				), API::STATUS_BAD_REQUEST);
			}

			Platform::messages()->error('No items hierarchy was provided.');

			return Redirect::to_secure(ADMIN.'/menus'.(($slug) ? '/edit/'.$slug : null));
		}

		// Prepare our items
		$items = array();

		foreach ($input_hierarchy as $item)
		{
			$this->process_item_recursively($item, $items);
		}

		// Prepare data for the API
		$data = array();

		if ($name = Input::get('name'))
		{
			$data['name'] = $name;
		}
		if ($_slug = Input::get('slug'))
		{
			$data['slug'] = $_slug;
		}
		if (count($items) > 0)
		{
			$data['children'] = $items;
		}

		try
		{
			// If we're updating a menu
			if ($slug != false)
			{
				API::put('menus/'.$slug, $data);
			}
			else
			{
				API::post('menus', $data);
			}
		}
		catch (APIClientException $e)
		{
			if (Request::ajax())
			{
				return new Response(array(
					'message' => $e->getMessage(),
				), $e->getCode());
			}
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			return Redirect::to_secure(ADMIN.'/menus'.(($slug) ? '/edit/'.$slug : null));
		}

		return Redirect::to_secure(ADMIN.'/menus');
	}

	public function get_delete($slug)
	{
		try
		{
			API::delete('menus/'.$slug);
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}
		}

		return Redirect::to_secure(ADMIN.'/menus');
	}

	/**
	 * Recursively processes an item and it's children
	 * based on POST data.
	 *
	 * @param   array  $item
	 * @param   array  $items
	 */
	protected function process_item_recursively($item, &$items)
	{
		$new_item = array(
			'name'   => Input::get('item_fields.'.$item['id'].'.name'),
			'slug'   => Input::get('item_fields.'.$item['id'].'.slug'),
			'uri'    => Input::get('item_fields.'.$item['id'].'.uri'),
			'target' => Input::get('item_fields.'.$item['id'].'.target', 0),
			'type'   => Input::get('item_fields.'.$item['id'].'.type', 0),
			'status' => Input::get('item_fields.'.$item['id'].'.status', 1),
		);

		// Determine if we're a new item or not. If we're
		// new, we don't attach an ID. Nesty will handle the
		// rest.
		if ( ! Input::get('item_fields.'.$item['id'].'.is_new'))
		{
			$new_item['id'] = $item['id'];
		}

		// Now, look for secure URLs
		if (URL::valid($new_item['uri']))
		{
			$new_item['secure'] = (int) starts_with($new_item['uri'], 'https://');
		}

		// Relative URL, look in the POST data
		else
		{
			$new_item['secure'] = Input::get('item_fields.'.$item['id'].'.secure', 0);
		}

		// If we have children, call the function again
		if (isset($item['children']) and is_array($item['children']) and count($item['children']) > 0)
		{
			$children = array();

			foreach ($item['children'] as $child)
			{
				$this->process_item_recursively($child, $children);
			}

			$new_item['children'] = $children;
		}

		$items[] = $new_item;
	}

}
