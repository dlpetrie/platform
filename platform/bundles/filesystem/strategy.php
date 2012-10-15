<?php

namespace Filesystem;

class Strategy
{
	/**
	 * Strategy Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\Strategy Object
	 */
	protected $strategy = null;

	/**
	 * Fallback Strategy Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\Strategy Object
	 */
	protected $fallback_strategy = null;

	/**
	 * Directory Object
	 *
	 * @access   protected
	 * @var      Directory Object
	 */
	protected $directory = null;

	/**
	 * File Object
	 *
	 * @access   protected
	 * @var      File Object
	 */
	protected $file = null;

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Initialize Strategy Class
	 *
	 * @access   public
	 * @param    string
	 * @param    array
	 * @return   Filesystem\Strategy\{Driver}\Strategy Object
	 */
	public function __construct($driver = null, $settings = array())
	{
		$driver = ucfirst(($driver) ?: \Config::get('filesystem::filesystem.default_driver'));

		$class = 'Filesystem\\Strategy\\'.$driver.'\\Strategy';
		$this->strategy = new $class($settings);

		// set fallback if not already on native
		if ($driver != 'Native')
		{
			$fallback_class = 'Filesystem\\Strategy\\Native\\Strategy';

			// we still set fallback for when commands fail
			$this->fallback_strategy = new $fallback_class($settings);

			// use fallback if strategy doesn't exist
			if ( ! class_exists($class) or ! $this->strategy->isConnected())
			{
				\Event::fire(\Config::get('filesystem::events.strategy.fallback'));
				$this->strategy = new $fallback_class($settings);
			}
		}

		return $this;
	}

	/**
	 * -----------------------------------------
	 * Function: directory()
	 * -----------------------------------------
	 *
	 * Get the Directory Object
	 *
	 * @access   public
	 * @return   Filesystem\Directory Object
	 */
	public function directory()
	{
		if ( ! $this->directory)
		{
			$this->directory = new Directory($this->strategy, $this->fallback_strategy);
		}

		return $this->directory;
	}

	/**
	 * -----------------------------------------
	 * Function: file()
	 * -----------------------------------------
	 *
	 * Get the File Object
	 *
	 * @access   public
	 * @return   Filesystem\File Object
	 */
	public function file()
	{
		if ( ! $this->file)
		{
			$directory = ($this->directory) ?: $this->directory();

			$this->file = new File($this->strategy, $this->fallback_strategy);
		}

		return $this->file;
	}

	/**
	 * -----------------------------------------
	 * Function: getStrategy()
	 * -----------------------------------------
	 *
	 * Get the driver's strategy object
	 *
	 * @access   public
	 * @return   Filesystem\Strategy\{Driver}\Strategy Object
	 */
	public function getStrategy()
	{
		return $this->strategy;
	}
}