<?php
/**
 * Part of the Logs Extension for Platform.
 *
 * @package    Logs
 * @version    1.0
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Menus\Menu;

class Logs_Install
{

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		// create logs table
		Schema::create('logs', function($table)
		{
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('is_admin');
			$table->string('extension');
			$table->string('type');
			$table->text('message');
			$table->integer('timestamp');
		});

		// Find the system menu
		$system = Menu::find(function($query)
		{
			return $query->where('slug', '=', 'admin-system');
		});

		// logs menu
		$logs = new Menu(array(
			'name'          => 'Logs',
			'extension'     => 'logs',
			'slug'          => 'admin-logs',
			'uri'           => 'logs',
			'user_editable' => 0,
			'status'        => 1,
		));

		$logs->last_child_of($system);

		// Add base theme configuration options
		$is_admin_yes = DB::table('settings')->insert(array(
			'extension' => 'logs',
			'type'      => 'status',
			'name'      => 'true',
			'value'     => 1,
		));

		$is_admin_no = DB::table('settings')->insert(array(
			'extension' => 'logs',
			'type'      => 'status',
			'name'      => 'false',
			'value'     => 0,
		));

	}
	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		// delete table
		Schema::drop('logs');

		// Remove base theme configuration options
		$delete_config = DB::table('settings')
			->where('extension', '=', 'logs')
			->delete();
	}

}
