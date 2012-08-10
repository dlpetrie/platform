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

namespace Platform\Themes;

use Config;
use Crud;
use Str;
use Theme as BundleTheme;

/**
 * @todo Either use `name` or `theme` as the
 *       reference for a theme's name
 *       throughout API/controllers/models,
 *       currently it's both (`name` in controllers
 *       and `theme` in Model).
 *
 * - Ben Corlett
 */
class Theme extends Crud
{

	/**
	 * The name of the table associated with the model.
	 * If left null, the table name will become the the plural of
	 * the class name: user => users
	 *
	 * @var string
	 */
	protected static $_table = 'theme_options';

	/**
	 * The path for the theme_options.css file.
	 *
	 * @var string
	 */
	protected static $_filepath;

	/**
	 * Content for the theme_options.css file.
	 *
	 * @var string
	 */
	protected static $_css_content;

	/**
	 * Array of theme types
	 *
	 * @var string
	 */
	protected static $_types = array('backend', 'frontend');

	/**
	 * Called right after validation before inserting/updating to the database
	 *
	 * @param   array  $attributes
	 * @return  array
	 */
	protected function prep_attributes($attributes)
	{
		// generate css file contents
		$options = $attributes['options'];

		foreach ($options as $id => $option)
		{
			$selector = $options[$id]['selector'];
			$styles = '';
			foreach ($option['styles'] as $attribute => $value)
			{
				$styles .= "\t".$attribute.': '.$value.';'."\n";
			}
			static::$_css_content .= $selector.' {'."\n".$styles.'}'."\n\n";
		}

		// Get compile dir from theme bundle
		$compile_dir = str_finish(Config::get('theme::theme.compile_directory'), DS);

		// Set path for css file
		static::$_filepath = BundleTheme::directory().$compile_dir.$attributes['type'].DS.$attributes['theme'].DS.'assets'.DS.'css'.DS.'theme_options.css';

		// Encode options for db storage
		$attributes['options'] = json_encode($attributes['options']);

		return $attributes;
	}

	/**
	 * Gets call after the insert() query is exectuted to modify the result
	 * Must return a proper result
	 *
	 * @param   array  $result
	 * @return  array  $result
	 */
	protected function after_insert($result)
	{
		if ($result)
		{
			// Find css file and rewrite contents
			file_put_contents(static::$_filepath, static::$_css_content);
		}

		return $result;
	}

	/**
	 * Gets call after the update() query is exectuted to modify the result
	 * Must return a proper result
	 *
	 * @return  array
	 */
	protected function after_update($result)
	{
		if ($result)
		{
			// find css file and rewrite contents
			file_put_contents(static::$_filepath, static::$_css_content);
		}

		return $result;
	}

	/**
	 * Gets call after the find() query is exectuted to modify the result
	 * Must return a proper result
	 *
	 * @param   Query  $query
	 * @param   array  $columns
	 * @return  array
	 */
	protected function after_find($result)
	{
		if ($result)
		{
			$result->options = ($result->options) ? json_decode($result->options) : array();
			$result->status = (bool) $result->status;
		}

		return $result;
	}

	/**
	 * Fetches one or more themes in a given
	 * type (frontend / backend)
	 *
	 * @param   string  $type
	 * @param   string  $name
	 * @return  array
	 */
	public static function fetch($type, $name = null)
	{
		// Get list of names
		$theme_list = BundleTheme::all($type);

		// Returning one theme?
		if ($name != null)
		{
			if ( ! in_array($name, $theme_list))
			{
				return false;
			}

			return array_merge(array(
				'theme'       => $name,
				'name'        => Str::title($name),
				'description' => null,
				'author'      => null,
				'version'     => '1.0',
			), BundleTheme::info($type.DS.$name));
		}

		// Array of theme info's
		$themes = array();

		// Loop through the list and add to
		// info
		foreach ($theme_list as $theme)
		{
			// Add to theme
			$themes[] = array_merge(array(
				'theme'       => $theme,
				'name'        => Str::title($theme),
				'description' => null,
				'author'      => null,
				'version'     => '1.0',
			), BundleTheme::info($type.DS.$theme));
		}

		return $themes;
	}

	/**
	 * Returns an array of theme types.
	 *
	 * @return  array
	 */
	public static function types()
	{
		return static::$_types;
	}

}
