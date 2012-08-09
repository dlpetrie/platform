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

class Extensions_API_Extensions_Controller extends API_Controller
{

	/**
	 * Returns an array of all extensions,
	 * installed and uninstalled. You can provide
	 * an optional key (filter) with a value. Providing
	 * a slug as the second parameter will return that
	 * extension itself.
	 *
	 * This filter can either be:
	 *  - uninstalled
	 *  - installed
	 *  - disabled
	 *  - enabled
	 *
	 *	<code>
	 *		$all         = API::get('extensions');
	 *		$enabled     = API::get('extensions', array(
	 *			'filter' => 'enabled',
	 *		));
	 *		$uninstalled = API::get('extensions', array(
	 *			'filter' => 'uninstalled',
	 *		));
	 *		$users       = API::get('extensions/users');
	 *	</code>
	 *
	 * @return  Response
	 */
	public function get_index($slug = false)
	{
		// Returning all extensions
		if ($slug == false)
		{
			// Array of extensions
			$extensions = array();

			// If we have a filter, populate our extensions array
			if ($filter = Input::get('filter'))
			{
				if (in_array($filter, array('uninstalled', 'installed', 'disabled', 'enabled')))
				{
					// Get the extensions
					foreach (Platform::extensions_manager()->$filter() as $extension)
					{
						// Remove callbacks as they're no use
						// in JSON
						array_forget($extension, 'listeners');
						array_forget($extension, 'global_routes');

						$extensions[] = $extension;
					}

					return new Response($extensions);
				}

				return new Response(array(
					'message' => 'Invalid extension filter provided.',
				), API::STATUS_BAD_REQUEST);
			}

			// No filter, return all extensions
			else
			{
				foreach (Platform::extensions_manager()->all() as $extension)
				{
					// Remove callbacks as they're no use
					// in JSON
					array_forget($extension, 'listeners');
					array_forget($extension, 'global_routes');

					$extensions[] = $extension;
				}
			}

			// Sort the extensions
			ksort($extensions);

			// Only return array keys. This is because
			// we should return a JSON array. Named keys
			// returns an object.
			return new Response(array_values($extensions));
		}

		// Doesn't exist? Throw a 404
		if ( ! Platform::extensions_manager()->find_extension_file($slug))
		{
			return new Response(array(
				'message' => "Extension [$slug] doesn't exist.",
			), API::STATUS_NOT_FOUND);
		}

		try
		{
			$extension = Platform::extensions_manager()->get($slug);

			// Remove callbacks as they're no use
			// in JSON
			array_forget($extension, 'listeners');
			array_forget($extension, 'global_routes');

			return new Response($extension);
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Installs an extension by the given slug.
	 *
	 * @param  string  $slug
	 * @return
	 */
	public function post_install()
	{
		$slug = Input::get('slug', function()
		{
			throw new Exception('Invalid slug provided.');
		});

		try
		{
			$extension = Platform::extensions_manager()->install($slug, true);
		}
		catch (Exception $e)
		{
			return array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
		}

		return array(
			'status'    => true,
			'extension' => $extension,
		);
	}

	/**
	 * Uninstalls an extension
	 *
	 * @param  string  $slug
	 * @return
	 */
	public function post_uninstall()
	{
		$id = Input::get('id', function()
		{
			throw new Exception('Invalid id provided.');
		});

		try
		{
			return array(
				'status' => Platform::extensions_manager()->uninstall($id),
			);
		}
		catch (Exception $e)
		{
			return array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Checks if extensions have updates
	 *
	 * @return array
	 */
	public function get_updates()
	{
		$extensions = Input::get('extensions');

		foreach ($extensions as &$extension)
		{
			$extension['update'] = Platform::extensions_manager()->has_update($extension['slug']);
		}

		return $extensions;
	}

	public function post_update()
	{
		$id = Input::get('id');

		Platform::extensions_manager()->update($id);
	}

	public function post_enable()
	{
		$id = Input::get('id', function()
		{
			throw new Exception('Invalid id provided.');
		});

		try
		{
			$extension = Platform::extensions_manager()->enable($id);
		}
		catch (Exception $e)
		{
			return array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
		}

		return array(
			'status'    => true,
			'extension' => $extension,
		);
	}

	public function post_disable()
	{
		$id = Input::get('id', function()
		{
			throw new Exception('Invalid id provided.');
		});

		try
		{
			$extension = Platform::extensions_manager()->disable($id);
		}
		catch (Exception $e)
		{
			return array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
		}

		return array(
			'status'    => true,
			'extension' => $extension,
		);
	}

	/**
	 * Returns fields required for a
	 * Platform.table
	 *
	 * @return  Response
	 */
	public function get_datatable()
	{
		// CartTable defaults
		$defaults = array(
			'select'    => array(
				'id'          => Lang::line('extensions::extensions.table.id')->get(),
				'name'        => Lang::line('extensions::extensions.table.name')->get(),
				'slug'        => Lang::line('extensions::extensions.table.slug')->get(),
				'author'      => Lang::line('extensions::extensions.table.author')->get(),
				'description' => Lang::line('extensions::extensions.table.description')->get(),
				'version'     => Lang::line('extensions::extensions.table.version')->get(),
				'is_core'     => Lang::line('extensions::extensions.table.is_core')->get(),
				'enabled'     => Lang::line('extensions::extensions.table.enabled')->get(),
			),
			'where'     => array(),
			'order_by'  => array('slug' => 'asc'),
		);

		// Get total count
		$count_total = Extension::count();

		// Get the filtered count
		$count_filtered = Extension::count('id', function($query) use($defaults)
		{
			// Sets the were clause from passed settings
			$query = Table::count($query, $defaults);

			return $query;
		});

		// Paging
		$paging = Table::prep_paging($count_filtered, 20);

		// Get Table items
		$items = Extension::all(function($query) use ($defaults, $paging)
		{
			list($query, $columns) = Table::query($query, $defaults, $paging);

			return $query->select($columns);
		});

		// Get items
		$items = ($items) ?: array();

		// Return our data
		return new Response(array(
			'columns'        => $defaults['select'],
			'rows'           => $items,
			'count'          => $count_total,
			'count_filtered' => $count_filtered,
			'paging'         => $paging,
		));
	}

}
