<?php
/**
 * Part of the Logs Extension for Platform.
 *
 * @package    Logs
 * @version    1.0
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Logs\Libraries;

use API;
use Event;
use Platform;
use Request;
use Sentry;
use Uri;

class Helpers
{
	public static function find_events()
	{
		// find extensions
		$extensions = Platform::extensions_manager()->enabled();

		// find extension events
		$events = array();

		foreach ($extensions as $extension)
		{
			// if the extension has events, store them
			if (array_key_exists('events', $extension))
			{
				foreach ($extension['events'] as $event)
				{
					$events[] = $event;
				}
			}
		}

		static::create_listeners($events);
	}

	protected static function create_listeners($events)
	{
		// now that we have the events, add their listeners
		foreach($events as $event)
		{
			Event::listen($event, function($data) use($event) {

				$data = (array) $data;

				$names = explode('.', $event);
				$type = array_pop($names);

				$message = ucwords($type.' '.implode(' ', $names));

				if (isset($data['id']))
				{
					$message .= ' - Id: '.$data['id'];
				}
				elseif (is_array($data) and isset($data[0]['id']))
				{
					$message .= ' - Id: '.$data['id'];
				}

				$input = array(
					'user_id'   => Sentry::user()->id,
					'is_admin'  => strtolower(Uri::segment(1)) === strtolower(ADMIN),
					'extension' => Request::route()->bundle,
					'type'      => $type,
					'message'   => $message,
					'timestamp' => time()
				);

				// lets only add admin events for now
				if ($input['is_admin'])
				{
					API::post('logs/create', $input);
				}
			});
		}
	}
}
