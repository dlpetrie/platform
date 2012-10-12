<?php

namespace Filesystem\Driver;

class Strategy
{
	/**
	 * Driver
	 */
	protected $driver = null;

	/**
	 * Directory Object
	 */
	protected $directory = null;

	/**
	 * File Object
	 */
	protected $file = null;

	/**
	 * Driver Settings
	 */
	protected $settings = array();

	/**
	 * Connected
	 */
	protected $connected = false;

	/**
	 * -----------------------------------------
	 * Initialize FTP Class
	 * -----------------------------------------
	 */
	public function __construct($settings = array())
	{
		$this->setSettings($settings);

		return $this;
	}

	/**
	 * -----------------------------------------
	 * Get Driver
	 * -----------------------------------------
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * -----------------------------------------
	 * Is Connected
	 * -----------------------------------------
	 */
	public function isConnected()
	{
		return $this->connected;
	}

	/**
	 * -----------------------------------------
	 * Set Settings
	 * -----------------------------------------
	 *
	 * @param   array  FTP Settings
	 */
	public function setSettings($settings = array())
	{
		foreach ($this->settings as $key => $value)
		{
			if (array_key_exists($key, $settings))
			{
				$this->settings[$key] = $settings[$key];
			}
		}
	}

	/**
	 * -----------------------------------------
	 * Get Settings
	 * -----------------------------------------
	 *
	 * @param   array  FTP Settings
	 */
	public function getSettings($settings = array())
	{
		return $this->settings;
	}

}