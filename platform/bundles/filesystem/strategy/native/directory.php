<?php

namespace Filesystem\Strategy\Native;

class Directory extends \Filesystem\Driver\Directory
{
	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate the Directory Object
	 *
	 * @access   public
	* @param    Filesystem\Strategy\Native\Strategy Object
	 * @return   Filesystem\Strategy\Native\Directory Object
	 */
	public function __construct($strategy = null)
	{
		// change current directory outside of the platform folder
		$this->change(path('base').'../');

		return $this;
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
	public function make($path)
	{
		return ( ! is_dir($path)) ? mkdir($path, 0777, true) : true;
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
	public function delete($path, $preserve = false)
	{
		if ( ! is_dir($path))
		{
			return false;
		}

		$path = ($path) ?: $this->current();

		$contents = new \DirectoryIterator($path);

		foreach ($contents as $item)
		{
			if ($item->isDot())
			{
				continue;
			}
			elseif ($item->isDir())
			{
				$this->delete($item->getRealPath());
			}
			else
			{
				unlink($item->getRealPath());
			}
		}

	 	// delete the directory if we are just not cleaning it
	 	if ( ! $preserve)
	 	{
	 		rmdir($path);
	 	}
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
		$path = (is_null($path)) ? $this->current() : $path;

		return $this->delete($path, true);
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
		if ( ! is_dir($from))
		{
			return false;
		}

		return rename($from, $to);
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
		return getcwd();
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
		$path = (is_null($path)) ? $this->current() : $path;

		if ( ! is_dir($path))
		{
			return false;
		}

		// if path is not set, use the current path
		$path = ($path) ?: $this->current();

		// make a dirs and files array to seperate them,
		$dirs = array();
		$files = array();

		// iterate through file contents
		$contents = new \DirectoryIterator($path);

		foreach ($contents as $item)
		{
			if ($item->isDot())
			{
				// skip over '.' folders
				continue;
			}
			elseif ($item->isDir())
			{
				$dirs[] = $item->getFilename();
			}
			else
			{
				$files[] = $item->getFilename();
			}
		}

		// merge the two arrays, files last and return
		return array_merge($dirs, $files);
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
		if ( ! is_dir($path))
		{
			return false;
		}

		return chdir($path);
	}

}