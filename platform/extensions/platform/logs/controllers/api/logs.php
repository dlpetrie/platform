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

use Logs\Log;

class Logs_API_Logs_Controller extends API_Controller
{

	public function post_create()
	{
		$log_data = Input::get();

		$log = new Log($log_data);

		// save log
		try
		{
			$log->save();

			// we don't do anything if the the log fails, failed logging should
			// not break application flow
		}
		catch (\Exception $e)
		{
			// we don't do anything if there was an exception, failed logging should
			// not break application flow. We'll just catch the exception and move on.
		}
	}

	public function get_datatable()
	{
		$defaults = array(
			'select' => array(
				'logs.id'   => Lang::line('logs::table.id')->get(),
				'users_metadata.first_name' => Lang::line('logs::table.first_name')->get(),
				'users_metadata.last_name'  => Lang::line('logs::table.last_name')->get(),
				'settings.name'  => Lang::line('logs::table.is_admin')->get(),
				'logs.extension' => Lang::line('logs::table.extension')->get(),
				'logs.type'      => Lang::line('logs::table.type')->get(),
				'message'   => Lang::line('logs::table.message')->get(),
				'timestamp' => Lang::line('logs::table.timestamp')->get(),
			),
			'alias'     => array(
				'settings.name' => 'admin_log'
			),
			'where'     => array(),
			'order_by'  => array('logs.id' => 'desc'),
		);

		// lets get to total user count
		$count_total = Log::count();

		// get the filtered count
		// we set to distinct because a user can be in multiple groups
		$count_filtered = Log::count_distinct('logs.id', function($query) use ($defaults)
		{
			// sets the where clause from passed settings
			$query = Table::count($query, $defaults);

			return $query
				->left_join('users_metadata', 'users_metadata.user_id', '=', 'logs.user_id')
				->left_join('settings', 'settings.value', '=', 'logs.is_admin')
				->where('settings.extension', '=', 'logs')
				->where('settings.type', '=', 'status');
		});

		// set paging
		$paging = Table::prep_paging($count_filtered, 20);

		$items = Log::all(function($query) use ($defaults, $paging)
		{
			list($query, $columns) = Table::query($query, $defaults, $paging);

			return $query
				->select($columns)
				->left_join('users_metadata', 'users_metadata.user_id', '=', 'logs.user_id')
				->left_join('settings', 'settings.value', '=', 'logs.is_admin')
				->where('settings.extension', '=', 'logs')
				->where('settings.type', '=', 'status');

		});

		$items = ($items) ?: array();

		return array(
			'columns'        => $defaults['select'],
			'rows'           => $items,
			'count'          => $count_total,
			'count_filtered' => $count_filtered,
			'paging'         => $paging,
		);
	}
}
