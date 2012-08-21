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

class Logs_Admin_Logs_Controller extends Admin_Controller
{
	/**
	 * This function is called before the action is executed.
	 *
	 * @return void
	 */
	public function before()
	{
		parent::before();
		$this->active_menu('admin-logs');
	}

	public function get_index()
	{
		// Grab our datatable
		$datatable = API::get('logs/datatable', Input::get());

		$data = array(
			'columns' => $datatable['columns'],
			'rows'    => $datatable['rows'],
		);

		// If this was an ajax request, only return the body of the datatable
		if (Request::ajax())
		{
			return json_encode(array(
				"content"        => Theme::make('logs::partials.table_logs', $data)->render(),
				"count"          => $datatable['count'],
				"count_filtered" => $datatable['count_filtered'],
				"paging"         => $datatable['paging'],
			));
		}

		return Theme::make('logs::index', $data);
	}

}
