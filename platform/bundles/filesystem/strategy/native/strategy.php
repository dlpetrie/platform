<?php

namespace Filesystem\Strategy\Native;

use Filesystem;
use Filesystem\Strategy\Native\Directory;
use Filesystem\Strategy\Native\File;

class Strategy extends Filesystem\Driver\Strategy
{
	/**
	 * Driver
	 */
	protected $driver = 'Native';

	/**
	 * Connected
	 */
	protected $connected = true;
}