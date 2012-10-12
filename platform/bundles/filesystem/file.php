<?php

namespace Filesystem;

class File
{
	/**
	 * File Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\File Object
	 */
	protected $file = null;

	/**
	 * Fallback File Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\File Object
	 */
	protected $fallback = null;

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate the File Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy Object
	 * @param    Filesystem\Strategy Object
	 * @return   File Object
	 */
	public function __construct($strategy, $fallback = null)
	{
		$class = '\\Filesystem\\Strategy\\'.$strategy->getDriver().'\\File';

		if ($fallback)
		{
			$fallback_class = '\\Filesystem\\Strategy\\'.$fallback->getDriver().'\\File';
			$this->fallback = new $fallback_class($fallback);
		}

		$this->file = new $class($strategy);

		return $this;
	}

	/**
	 * -----------------------------------------
	 * Function: call()
	 * -----------------------------------------
	 *
	 * Call function with fallback
	 *
	 * @access   protected
	 * @param    string
	 * @return   mixed
	 */
	protected function call($method)
	{
		$args = func_get_args();
		array_shift($args);

		$response = call_user_func_array(array($this->file, $method), $args);

		if ( ! $response and ! is_null($this->fallback))
		{
			$response = call_user_func_array(array($this->fallback, $method), $args);
		}

		return $response;
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
		return $this->call('rename',Filesystem::findPath($from), Filesystem::findPath($to));
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
	public function make($path, $contents = null)
	{
		return $this->call('make', Filesystem::findPath($path), $contents);
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
		return $this->call('delete', Filesystem::findPath($path));
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
		return $this->call('rename', Filesystem::findPath($from), Filesystem::findPath($to));
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
	public function contents($path)
	{
		return $this->call('contents', Filesystem::findPath($path));
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
	public function write($path, $contents)
	{
		return $this->call('write', Filesystem::findPath($path), $contents);
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
		return $this->call('append', Filesystem::findPath($path), $contents);
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
		return $this->call('size', Filesystem::findPath($path));
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
		return $this->call('exists', Filesystem::findPath($path));
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
		return $this->call('modified', Filesystem::findPath($path));
	}
}