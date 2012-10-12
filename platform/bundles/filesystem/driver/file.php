<?php

namespace Filesystem\Driver;

abstract class File
{
	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate the File Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy\{Driver}\Strategy Object
	 * @return   Filesystem\Strategy\{Driver}\File Object
	 */
	public function __construct($strategy = null)
	{
		return $this;
	}

	/**
	 * -----------------------------------------
	 * Function: move()
	 * -----------------------------------------
	 *
	 * Move a File
	 *
	 * @access   public
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function move($from, $to)
	{
		$this->rename($from, $to);
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
	abstract public function make($path, $contents = null);

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
	abstract public function delete($path);

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
	abstract public function rename($from, $to);

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
	abstract public function contents($path);

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
	abstract public function write($path, $contents);

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
	abstract public function append($path, $contents);

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
	abstract public function size($path);

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
	abstract public function exists($path);

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
	abstract public function modified($path);

}