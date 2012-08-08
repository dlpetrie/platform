<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Users\Group;

class Users_API_Groups_Controller extends API_Controller
{

	/**
	 * Returns an array of groups by the
	 * given filters or a single group
	 *
	 *	<code>
	 *		$groups = API::get('users/groups');
	 *		$group  = API::get('users/groups/:id');
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function get_index($id = false)
	{
		$config = Input::get() + array(
			'select'   => array('id', 'name'),
			'where'    => array(),
			'order_by' => array(),
			'take'     => null,
			'skip'     => 0,
		);

		// No ID? Return all groups
		if ($id == false)
		{
			$groups = Group::find_custom($config['select'], $config['where'], $config['order_by'], $config['take'], $config['skip']);

			return new Response($groups);
		}

		$config['take']  = 1;
		$config['where'] = array('id', '=', $id);

		$groups = Group::find_custom($config['select'], $config['where'], $config['order_by'], $config['take'], $config['skip']);

		if (empty($groups))
		{
			return new Response(Lang::line('users::groups.errors.no_groups_exist')->get(), API::STATUS_NOT_FOUND);
		}

		$group = $groups[0];
		return new Response($group);
	}

	/**
	 * Creates a group
	 *
	 *	<code>
	 *		API::post('users/groups', $data);
	 *	</code>
	 *
	 * @return  Response
	 */
	public function post_index()
	{
		// Create a group
		$group = new Group(Input::get());

		// Save group
		try
		{
			// Save the group
			if ($group->save())
			{
				return new Response($group, API::STATUS_CREATED);
			}
			else
			{
				return new Response(array(
					'message' => Lang::line('users::groups.create.error')->get(),
					'errors'  => ($group->validation()->errors->has()) ? $group->validation()->errors->all() : array(),
					), API::STATUS_BAD_REQUEST);
			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Updates a given group by the
	 * provided ID
	 *
	 *	<code>
	 *		API::put('users/groups/:id', $data);
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function put_index($id)
	{
		// Update a group
		$group = new Group(array_merge(array(
			'id' => $id,
		), Input::get()));

		// Save group
		try
		{
			// Save the group
			if ($group->save())
			{
				return new Response($group);
			}
			else
			{
				return new Response(array(
					'message' => Lang::line('groups::groups.update.error')->get(),
					'errors'  => ($group->validation()->errors->has()) ? $group->validation()->errors->all() : array(),
				), API::STATUS_UNPROCESSABLE_ENTITY);
			}
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Deletes a group by the
	 * given ID
	 *
	 *	<code>
	 *		API::delete('usrs/groups/:id');
	 *	</code>
	 *
	 * @param   int  $id
	 * @return  Response
	 */
	public function delete_index($id)
	{
		$group = Group::find($id);

		if ($group === null)
		{
			return new Response(array(
				'message' => Lang::line('groups::groups.general.not_found')->get()
			), API::STATUS_NOT_FOUND);
		}

		try
		{
			if ($group->delete())
			{
				return new Response(null, API::STATUS_NO_CONTENT);
			}

			return new Response(array(
				'message' => "An error occured while deleting the group [$id]",
				'errors'  => ($group->validation()->errors->has()) ? $group->validation()->errors->all() : array(),
			), API::STATUS_UNPROCESSABLE_ENTITY);
		}
		catch (Exception $e)
		{
			return new Response(array(
				'message' => $e->getMessage(),
			), API::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Returns fields required for a
	 * Platform.table
	 *
	 * @return  Response
	 */
	public function get_datatable()
	{
		$defaults = array(
			'select'    => array(
				'groups.id'     => Lang::line('users::groups.general.id')->get(),
				'name'          => Lang::line('users::groups.general.name')->get(),
			),
			'alias'     => array(
				'groups.id' => 'id',
			),
			'where'     => array(),
			'order_by'  => array('id' => 'desc'),
		);

		// lets get to total user count
		$count_total = Group::count();

		// get the filtered count
		// we set to distinct because a user can be in multiple groups
		$count_filtered = Group::count('groups.id', false, function($query) use ($defaults)
		{
			// sets the where clause from passed settings
			$query = Table::count($query, $defaults);

			return $query;
		});

		// set paging
		$paging = Table::prep_paging($count_filtered, 20);

		$items = Group::all(function($query) use ($defaults, $paging)
		{
			list($query, $columns) = Table::query($query, $defaults, $paging);

			return $query
				->select($columns);

		});

		$items = ($items) ?: array();

		return new Response(array(
			'columns'        => $defaults['select'],
			'rows'           => $items,
			'count'          => $count_total,
			'count_filtered' => $count_filtered,
			'paging'         => $paging,
		));
	}

}
