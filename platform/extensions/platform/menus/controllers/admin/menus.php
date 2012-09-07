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

		// Get all children
		try
		{
			$all_children = API::get('menus/flat');
		}
		catch (APIClientException $e)
		{
			$all_children = array();
		}

		// Get the last child's ID
		$last_child_id = array_get(end($all_children), 'id', 0);

		// Get array of persisted menu slugs. It's used
		// by javascript to validate unique slugs on
		// client end in addition to server end.
		$persisted_slugs = array();
		foreach ($all_children as $child)
		{
			$persisted_slugs[] = array_get($child, 'slug');
		}
		sort($persisted_slugs);

		// Return the edit view
		return Theme::make('menus::edit')
		            ->with('menu', $menu)
		            ->with('menu_slug', array_get($menu, 'slug', false))
		            ->with('last_child_id', $last_child_id)
		            ->with('root_slug', array_get($menu, 'slug', false))
		            ->with('persisted_slugs', $persisted_slugs);
	}

	/**
	 * Processed creating a menu.
	 *
	 * @return  Response
	 */
	public function post_create()
	{
		return $this->post_edit();
	}

	/**
	 * Processes editing a menu.
	 *
	 * @param   string  $slug
	 * @return  mixed
	 */
	public function post_edit($slug = false)
	{
		$input_hierarchy = Input::get('children_hierarchy');

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
					'message' => Lang::line('menus::messages.update.no_chidren'),
				), API::STATUS_BAD_REQUEST);
			}

			Platform::messages()->error('No children hierarchy was provided.');

			if (Request::ajax())
			{
				return new Response(array(
					'message' => Lang::line('menus::messages.update.no_chidren'),
				), API::STATUS_BAD_REQUEST);
			}

			return Redirect::to_secure(ADMIN.'/menus'.(($slug) ? '/edit/'.$slug : null));
		}

		// Prepare our children
		$children = array();

		foreach ($input_hierarchy as $child)
		{
			$this->process_child_recursively($child, $children);
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
		if ($class = Input::get('class'))
		{
			$data['class'] = $class;
		}
		if (count($children) > 0)
		{
			$data['children'] = $children;
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


		if (Request::ajax())
		{
			return new Response(null, API::STATUS_NO_CONTENT);
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
	 * Recursively processes an child and it's children
	 * based on POST data.
	 *
	 * @param   array  $child
	 * @param   array  $children
	 */
	protected function process_child_recursively($child, &$children)
	{
		$new_child = array(
			'name'       => Input::get('children.'.$child['id'].'.name'),
			'slug'       => Input::get('children.'.$child['id'].'.slug'),
			'uri'        => Input::get('children.'.$child['id'].'.uri'),
			'class'		 => Input::get('children.'.$child['id'].'.class'),
			'target'     => Input::get('children.'.$child['id'].'.target', 0),
			'visibility' => Input::get('children.'.$child['id'].'.visibility', 0),
			'status'     => Input::get('children.'.$child['id'].'.status', 1),
		);

		// Determine if we're a new child or not. If we're
		// new, we don't attach an ID. Nesty will handle the
		// rest.
		if ( ! Input::get('children.'.$child['id'].'.is_new'))
		{
			$new_child['id'] = $child['id'];
		}

		// Now, look for secure URLs
		if (URL::valid($new_child['uri']))
		{
			$new_child['secure'] = (int) starts_with($new_child['uri'], 'https://');
		}

		// Relative URL, look in the POST data
		else
		{
			$new_child['secure'] = Input::get('children.'.$child['id'].'.secure', 0);
		}

		// If we have children, call the function again
		if (isset($child['children']) and is_array($child['children']) and count($child['children']) > 0)
		{
			$grand_children = array();

			foreach ($child['children'] as $child)
			{
				$this->process_child_recursively($child, $grand_children);
			}

			$new_child['children'] = $grand_children;
		}

		$children[] = $new_child;
	}

}
