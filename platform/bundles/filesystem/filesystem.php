<?php

namespace Filesystem;

use Config;

/**
 * --------------------------------------------------------------------------
 * Filesystem Class
 * --------------------------------------------------------------------------
 *
 * A class interface for filesystem drivers
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Filesystem
{
	/**
	 * Filsystem Instances
	 *
	 * @access   protected
	 * @param    array
	 */
	protected static $instances = array();

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Prevent Class Instantiation
	 */
	final private function __construct() {}

	/**
	 * -----------------------------------------
	 * Function: make()
	 * -----------------------------------------
	 *
	 * Set and return Filesystem Driver
	 *
	 * @access   public
	 * @return   Filesystem\Strategy Object
	 */
	public static function make($driver = false, $settings = array())
	{
		$driver = ($driver) ?: Config::get('filesystem::filesystem.default_driver');

		// if an instance with the same settings exist, use it, but reset the dir
		if ( ! empty(static::$instances) and $strategy = static::findInstance($driver, $settings))
		{
			return $strategy;
		}

		$strategy = new Strategy($driver, $settings);

		static::setInstance($strategy, $driver, $settings);

		return $strategy;
	}

	/**
	 * -----------------------------------------
	 * Function: findPath()
	 * -----------------------------------------
	 *
	 * Convert Absolute Path to Relative
	 *
	 * @access   public
	 * @param    string
	 * @return   string
	 */
	public static function findPath($path)
	{
		$path = str_replace('/', DS, $path);
		$base = realpath($GLOBALS['laravel_paths']['base'].'..').DS;

		// check if its part of the laravel paths
		if (strpos($path, $base) !== false)
		{
			$path = str_replace($base, '', $path);
		}

		return $path;
	}

	/**
	 * -----------------------------------------
	 * Function: setInstance()
	 * -----------------------------------------
	 *
	 * Set Filesystem Instance
	 *
	 * @access   protected
	 * @param    Filesystem\Strategy Object
	 * @param    string
	 */
	protected static function setInstance($strategy, $driver)
	{
		static::$instances[] = array(
			'strategy' => $strategy,
			'values'   => array(
				'driver'   => $driver,
				'settings' => $strategy->getStrategy()->getSettings(),
			),
		);
	}

	/**
	 * -----------------------------------------
	 * Function: findInstance()
	 * -----------------------------------------
	 *
	 * Used to find an already existing instance to prevent unneeded reconnects
	 * and object creations.
	 *
	 * @access   protected
	 * @param    string
	 * @param    array
	 * @return   Filesystem\Strategy Object or False
	 */
	protected static function findInstance($driver, $settings)
	{
		$value = array(
			'driver'   => $driver,
			'settings' => ( ! empty($settings)) ? $settings : \Config::get('filesystem::filesystem.settings.'.strtolower($driver), array()),
		);

		foreach (static::$instances as $instance)
		{
			if ($instance['values'] == $value)
			{
				return $instance['strategy'];
			}
		}

		return false;
	}
}