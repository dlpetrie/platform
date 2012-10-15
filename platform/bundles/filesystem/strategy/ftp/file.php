<?php

namespace Filesystem\Strategy\FTP;

class File extends \Filesystem\Driver\File
{
	/**
	 * Connection Id
	 *
	 * @access   protected
	 * @var      connection resource id
	 */
	protected $connection_id = null;

	/**
	 * -----------------------------------------
	 * Function: __construct()
	 * -----------------------------------------
	 *
	 * Instantiate File Object
	 *
	 * @access   public
	 * @param    Filesystem\Strategy\Ftp\Strategy Object
	 * @return   Filesystem\Strategy\Ftp\File Object
	 */
	public function __construct(Strategy $strategy)
	{
		$this->connection_id = $strategy->getConnection();

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
		// make a temp file
		$tmp = tmpfile();

		// if contents were passed, add contents and reset pointer
		switch ($type)
		{
			case 'append':
				if (@ftp_fget($this->connection_id, $tmp, $path, FTP_ASCII))
				{
					fwrite($tmp, $contents);
				}
				else
				{
					return false;
				}
				break;
			default:
				fwrite($tmp, $contents);
				break;
		}

		fseek($tmp, 0);
		// echo $path;
		// exit;
		// we use FTP_ASCII since we are creating a text file in these scenarios
		$result = @ftp_fput($this->connection_id, $path, $tmp, FTP_ASCII);

		// remove temp file
		fclose($tmp);

		return $result;
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
		return @ftp_delete($this->connection_id, $path);
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
		return @ftp_rename($this->connection_id, $from, $to);
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
		$tmp = tmpfile();

		if (@ftp_fget($this->connection_id, $tmp, $path, FTP_ASCII))
		{
			fseek($tmp, 0);

			$size = $this->size($path);

			if ($size)
			{
				$contents = fread($tmp, $this->size($path));
			}
			else
			{
				$contents = '';
			}

			// remove temp file
			fclose($tmp);

			return $contents;
		}

		return false;
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
		$response = @ftp_size($this->connection_id, $path);

		$response = ($response == -1) ? false : $response;

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
		$contents = @ftp_nlist($this->connection_id, dirname($path));

		$contents = ($contents) ?: array();

		foreach ($contents as &$content)
		{
			$content = basename($content);
		}

		return in_array(basename($path), $contents);
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
		return @ftp_mdtm($this->connection_id, $path);
	}
}