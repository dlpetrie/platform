<?php

namespace Filesystem;

class Directory
{
	/**
	 * Directory Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\Directory Object
	 */
	protected $directory = null;

	/**
	 * Fallback Directory Object
	 *
	 * @access   protected
	 * @var      Filesystem\Strategy\{Driver}\Directory Object
	 */
	protected $fallback = null;

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate the Directory Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy Object
	 * @param    Filesystem\Strategy Object
	 * @return   Directory Ojbect
	 */
	public function __construct($strategy, $fallback = null)
	{
		$class = '\\Filesystem\\Strategy\\'.$strategy->getDriver().'\\Directory';

		if ($fallback)
		{
			$fallback_class = '\\Filesystem\\Strategy\\'.$fallback->getDriver().'\\Directory';
			$this->fallback = new $fallback_class($fallback);
		}

		$this->directory = new $class($strategy);

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

		$response = call_user_func_array(array($this->directory, $method), $args);

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
	 * Move a Directory
	 *
	 * @access   public
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function move($from, $to)
	{
		return $this->call('rename', Filesystem::findPath($from), Filesystem::findPath($to));
	}

	/**
	 * -----------------------------------------
	 * Function: make()
	 * -----------------------------------------
	 *
	 * Make a Directory
	 *
	 * @access   public
	 * @param    string
	 * @return   bool
	 */
	public function make($name)
	{
		return $this->call('make', Filesystem::findPath($name));
	}

	/**
	 * -----------------------------------------
	 * Function: delete()
	 * -----------------------------------------
	 *
	 * Delete a Directory
	 *
	 * @access   public
	 * @param    string  directory name
	 * @return   bool
	 */
	public function delete($path)
	{
		return $this->call('delete', Filesystem::findPath($path));
	}

	/**
	 * -----------------------------------------
	 * Function: clean()
	 * -----------------------------------------
	 *
	 * Clean a Directory
	 *
	 * @access   public
	 * @param    string
	 * @return   bool
	 */
	public function clean($path = null)
	{
		return $this->call('clean', Filesystem::findPath($path));
	}

	/**
	 * -----------------------------------------
	 * Function: rename()
	 * -----------------------------------------
	 *
	 * Rename a Directory
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
	 * Function: current()
	 * -----------------------------------------
	 *
	 * Get Current Directory
	 *
	 * @access   public
	 * @return   string
	 */
	public function current()
	{
		return $this->call('current');
	}

	/**
	 * -----------------------------------------
	 * Function: contents()
	 * -----------------------------------------
	 *
	 * Get Directory Contents
	 *
	 * @access   public
	 * @param    string
	 * @return   string
	 */
	public function contents($path = null)
	{
		return $this->call('contents', Filesystem::findPath($path));
	}

	/**
	 * -----------------------------------------
	 * Function: change()
	 * -----------------------------------------
	 *
	 * Change Directory
	 *
	 * @access   public
	 * @param    string
	 * @return   bool
	 */
	public function change($path)
	{
		return $this->call('change', Filesystem::findPath($path));
	}

}