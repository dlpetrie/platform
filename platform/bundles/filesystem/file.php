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
	protected function call()
	{
		$args = func_get_args();

		$method = array_shift($args);

		$response = call_user_func_array(array($this->file, $method), $args);

		if ( $response === false and ! is_null($this->fallback))
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event.fallback'),
				array(\Lang::line('filesystem::fallback.file.'.$method)->get())
			);
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
		$response = $this->call('rename', Filesystem::findPath($from), Filesystem::findPath($to));

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.move')->get())
			);
		}

		return $response;
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
		$response = $this->call('make', Filesystem::findPath($path), $contents);

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.make')->get())
			);
		}

		return $response;
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
		$response = $this->call('delete', Filesystem::findPath($path));

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.delete')->get())
			);
		}

		return $response;
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
		$response = $this->call('rename', Filesystem::findPath($from), Filesystem::findPath($to));

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.rename')->get())
			);
		}

		return $response;
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
		$response = $this->call('contents', Filesystem::findPath($path));

		if ($response === false)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.contents')->get())
			);
		}

		return $response;
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
		$response = $this->call('write', Filesystem::findPath($path), $contents);

		if ($response === false)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.write')->get())
			);
		}

		return $response;
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
		$response = $this->call('append', Filesystem::findPath($path), $contents);

		if ($response === false)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.append')->get())
			);
		}

		return $response;
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
		$response = $this->call('size', Filesystem::findPath($path));

		if ($response === false)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.size')->get())
			);
		}

		return $response;
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
		$response = $this->call('exists', Filesystem::findPath($path));

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.exists'))
			);
		}

		return $response;
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
		$response = $this->call('modified', Filesystem::findPath($path));

		if ( ! $response)
		{
			\Event::fire(
				\Config::get('filesystem::filesystem.event'),
				array(\Lang::line('filesystem::failed.file.modified'))
			);
		}

		return $response;
	}
}