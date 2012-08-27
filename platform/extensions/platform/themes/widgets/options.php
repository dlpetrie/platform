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

namespace Platform\Themes\Widgets;

use API;
use APIClientException;
use Request;
use Theme;
use URI;

class Options
{

	public function css()
	{
		$active_parts = explode(DS, ltrim(rtrim(Theme::active(), DS), DS));
		$type         = $active_parts[0];
		$name         = $active_parts[1];

		// Get active custom theme options
		try
		{
			$options = API::get('themes/'.$type.'/'.$name.'/options');
		}
		catch (APIClientException $e)
		{
			Platform::messages()->error($e->getMessage());

			foreach ($e->errors() as $error)
			{
				Platform::messages()->error($error);
			}

			// Options fallback
			$options = array();
		}

		// Get the active status from the database. The admin
		// compiles the theme_options.css file. We need to know
		// if it's actually active before including the file (done
		// in the view).
		$status = array_get($options, 'status', false);

		return Theme::make('themes::widgets.theme_options_css')
		            ->with('status', $status);
	}

}
