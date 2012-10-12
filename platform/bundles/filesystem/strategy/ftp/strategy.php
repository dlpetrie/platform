<?php

namespace Filesystem\Strategy\Ftp;

use Filesystem;
use Filesystem\Strategy\FTP\Directory;
use Filesystem\Strategy\FTP\File;

class Strategy extends Filesystem\Driver\Strategy
{
	/**
	 * Driver
	 */
	protected $driver = 'Ftp';

	// connection id
	//
	protected $connection_id = null;

	// FTP Settings
	//
	protected $settings = array(
		'server'   => null,
		'user'     => null,
		'password' => null,
		'port'     => 21,
		'timeout'  => 5,
	);

	/**
	 * Connected
	 */
	protected $connected = false;

	public function __construct($settings = array())
	{
		if (empty($settings))
		{
			$settings = \Config::get('filesystem::filesystem.settings.ftp');
		}

		$this->setSettings($settings);
		$this->connect();

		return $this;
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * -----------------------------------------
	 * Connect to FTP
	 * -----------------------------------------
	 */
	public function connect()
	{
		extract($this->settings);

		// connect to the FTP server
		$this->connection_id = @ftp_connect($server, $port, $timeout);

		// and now login
		$response = @ftp_login($this->connection_id, $user, $password);

		@ftp_pasv($this->connection_id, true);

		// set connections status
		$this->connected = $response;
		echo 'connected';
		return $response;
	}

	/**
	 * -----------------------------------------
	 * Disconnect from FTP
	 * -----------------------------------------
	 */
	public function disconnect()
	{
		return @ftp_close($this->connection_id);
	}

	/**
	 * -----------------------------------------
	 * get Connection
	 * -----------------------------------------
	 */
	public function getConnection()
	{
		return $this->connection_id;
	}

	// /**
	//  * -----------------------------------------
	//  * Get FTP Directory Object
	//  * -----------------------------------------
	//  */
	// public function directory()
	// {
	// 	echo 'hi';
	// 	exit;
	// 	if ( ! $this->directory)
	// 	{
	// 		$this->directory = new Directory(array(
	// 			'connection' => $this->connection_id
	// 		));
	// 	}

	// 	return $this->directory;
	// }

	// *
	//  * -----------------------------------------
	//  * Get FTP File Object
	//  * -----------------------------------------

	// public function file()
	// {
	// 	if ( ! $this->file)
	// 	{
	// 		$directory = ($this->directory) ?: $this->directory();

	// 		$this->file = new File(array(
	// 			'connection' => $this->connection_id,
	// 			'directory'  => $this->directory,
	// 		));
	// 	}

	// 	return $this->file;
	// }

}