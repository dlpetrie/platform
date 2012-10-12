<?php

namespace Filesystem\Strategy\FTP;

class Directory extends \Filesystem\Driver\Directory
{
	protected $connection_id = null;

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate the Directory Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy\Ftp\Strategy Object
	 * @return   Filesystem\Strategy\Ftp\Directory Object
	 */
	public function __construct(Strategy $strategy)
	{
		$this->change(\Filesystem::findPath(path('base').'../'));
		$this->connection_id = $strategy->getConnection();

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
		return @ftp_mkdir($this->connection_id, $path);
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
		if ( ! (@ftp_rmdir($this->connection_id, $path) || @ftp_delete($this->connection_id, $path)))
        {
			$contents = $this->contents($path);

			if ( ! empty($contents))
			{
				foreach ($contents as $item)
				{
					$this->delete($item);
				}
			}
        }

        if ( ! $preserve)
        {
    		@ftp_rmdir($this->connection_id, $path);
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
		return @ftp_rename($this->connection_id, $from, $to);
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
		return str_finish(@ftp_pwd($this->connection_id), DS);
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
		// if path is not set, use the current path
		$path = ($path) ?: $this->current();

		return @ftp_nlist($this->connection_id, $path);
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
		return @ftp_chdir($this->connection_id, $path);
	}

}