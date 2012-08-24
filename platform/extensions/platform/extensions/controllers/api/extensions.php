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
					'message' => Lang::line('extensions::messages.errors.invalid_filter')->get()
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
				'message' => Lang::line('extensions::messages.errors.does_not_exist', array(
					'slug' => $slug
				))->get(),
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
	 * Updates an extension based on the parameters
	 * passed.
	 *
	 *	<code>
	 *		// This installs an extension
	 *		API::put('extensions/users', array(
	 *			'installed' => true,
	 *		));
	 *
	 *		API::put('extensions/users', array(
	 *			'enabled' => false,
	 *		));
	 *
	 *		// Installed equalling false is...
	 *		API::put('extensions/users', array(
	 *			'installed' => false,
	 *		));
	 *
	 *		// The same as uninstalled equalling true.
	 *		// Be sure to only pick one or the other or
	 *		// You could override yourself.
	 *		API::put('extensions/users', array(
	 *			'uninstalled' => true,
	 *		));
	 *	</code>
	 *
	 * 'installed' is the opposite to 'uninstalled'. If
	 * both are provided, the value for 'installed' will
	 * be used.
	 *
	 * 'enabled' is the opposite to 'disabled'. If both
	 * are provided, the value for 'enabled' is used.
	 *
	 *
	 * @param   string  $slug
	 * @return  Response
	 */
	public function put_index($slug)
	{
		// Doesn't exist? Throw a 404
		if ( ! Platform::extensions_manager()->find_extension_file($slug))
		{
			return new Response(array(
				'message' => Lang::line('extensions::messages.errors.does_not_exist', array(
					'slug' => $slug
				))->get(),
			), API::STATUS_NOT_FOUND);
		}

		// Get the extension
		$extension = Platform::extensions_manager()->get($slug);

		// Flags for whether the extension
		// should become installed or enabled
		$installed = null;
		$enabled   = null;

		// If we have an installed key
		if ((($_installed = Input::get('installed')) !== null) or (($_uninstalled = Input::get('uninstalled')) !== null))
		{
			$installed = ($_installed !== null) ? (bool) $_installed : ( ! (bool) $_uninstalled);
		}

		// If we have an enabled key
		if ((($_enabled = Input::get('enabled')) !== null) or (($_disabled = Input::get('disabled')) !== null))
		{
			$enabled = ($_enabled !== null) ? (bool) $_enabled : ( ! (bool) $_disabled);
		}

		try
		{
			// If the person wants the extension installed
			if ($installed === true)
			{
				// Check if it's already been installed
				if ($extension['info']['installed'] != true)
				{
					$extension = Platform::extensions_manager()->install($slug);
				}
			}

			// The person wants the extension uninstalled
			elseif ($installed === false and $extension['info']['installed'] == true)
			{
				$extension = Platform::extensions_manager()->uninstall($slug);
			}

			// If they want it enabled or not. We only allow
			// that if the extension is installed
			if ($enabled !== null and $extension['info']['installed'] == true)
			{
				$method = ($enabled === true) ? 'enable' : 'disable';
				$extension = Platform::extensions_manager()->$method($slug);
			}

			// They want to update it
			if ($update = Input::get('update'))
			{
				$extension = Platform::extensions_manager()->update($slug);
			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}

		// Remove callbacks as they're no use
		// in JSON
		array_forget($extension, 'listeners');
		array_forget($extension, 'global_routes');

		return new Response($extension);
	}

}
