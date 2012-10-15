<?php

namespace Filesystem\Strategy\Native;

class File extends \Filesystem\Driver\File
{
	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate File Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy\Native\Strategy Object
	 * @return   Filesystem\Strategy\Native\File Object
	 */
	public function __construct($strategy = null)
	{
		return $this;
	}

	/**
	 * -----------------------------------------
	 * Function: make()
	 * -----------------------------------------
	 *
	 * Make a File
	 *
	 * @access   public
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function make($path, $contents = null, $type = 'default')
	{
		$dir  = dirname($path);

		if ( ! is_dir($dir))
		{
			mkdir($dir, 0777, true);
		}

		// if contents were passed, add contents and reset pointer
		switch ($type)
		{
			case 'append':
				return file_put_contents($path, $contents, LOCK_EX | FILE_APPEND);
				break;
			default:
				return file_put_contents($path, $contents, LOCK_EX);
				break;
		}
	}

	/**
	 * -----------------------------------------
	 * Function: delete()
	 * -----------------------------------------
	 *
	 * Delete a File
	 *
	 * @access   public
	 * @param    string   file name to delete
	 * @return   bool
	 */
	public function delete($path)
	{
		return (file_exists($path)) ? unlink($path) : true;
	}

	/**
	 * -----------------------------------------
	 * Function: rename()
	 * -----------------------------------------
	 *
	 * Rename a File
	 *
	 * @access   public
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function rename($from, $to)
	{
		return (file_exists($from)) ? rename($from, $to) : false;
	}

	/**
	 * -----------------------------------------
	 * Function: contents()
	 * -----------------------------------------
	 *
	 * Get File Conents
	 *
	 * @access   public
	 * @param    string
	 * @return   string
	 */
	public function contents($path, $default = null)
	{
		return (file_exists($path)) ? file_get_contents($path) : $default;
	}

	/**
	 * -----------------------------------------
	 * Function: write()
	 * -----------------------------------------
	 *
	 * Write to File
	 *
	 * @access   public
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function write($path, $contents, $location = null)
	{
		return $this->make($path, $contents);
	}

	/**
	 * -----------------------------------------
	 * Function: append()
	 * -----------------------------------------
	 *
	 * Append to File
	 *
	 * @access   public
	 * @param    string   file name to write to
	 * @param    string   contents to append to the file
	 * @return   bool
	 */
	public function append($path, $contents)
	{
		return $this->make($path, $contents, 'append');
	}

	/**
	 * -----------------------------------------
	 * Function: size()
	 * -----------------------------------------
	 *
	 * Get File Size
	 *
	 * @access   public
	 * @param    string
	 * @return   int
	 */
	public function size($path)
	{
		return (file_exists($path)) ? filesize($path) : false;
	}

	/**
	 * -----------------------------------------
	 * Function: exists()
	 * -----------------------------------------
	 *
	 * File Exists
	 *
	 * @access   public
	 * @param    string
	 * @return   bool
	 */
	public function exists($path)
	{
		return file_exists($path);
	}

	/**
	 * -----------------------------------------
	 * Function: modified()
	 * -----------------------------------------
	 *
	 * Last Modified
	 *
	 * @access   public
	 * @param    string
	 * @return   timestamp
	 */
	public function modified($path)
	{
		return (file_exists($path)) ? filemtime($path) : false;
	}
}