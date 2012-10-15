<?php

return array(

	// Strategy Events
	//
	'strategy' => array(
		'fallback' => 'filesystem_strategy_fallback',
	),

	// Directory Events
	//
	'directory' => array(

		// Fallback Events
		//
		'fallback' => array(
			'move'     => 'filesystem_directory_fallback_move',
			'make'     => 'filesystem_directory_fallback_make',
			'delete'   => 'filesystem_directory_fallback_delete',
			'clean'    => 'filesystem_directory_fallback_clean',
			'rename'   => 'filesystem_directory_fallback_rename',
			'current'  => 'filesystem_directory_fallback_current',
			'contents' => 'filesystem_directory_fallback_contents',
			'change'   => 'filesystem_directory_fallback_change',
		),

		// Failed Events
		//
		'failed' => array(
			'move'     => 'filesystem_directory_failed_move',
			'make'     => 'filesystem_directory_failed_make',
			'delete'   => 'filesystem_directory_failed_delete',
			'clean'    => 'filesystem_directory_failed_clean',
			'rename'   => 'filesystem_directory_failed_rename',
			'current'  => 'filesystem_directory_failed_current',
			'contents' => 'filesystem_directory_failed_contents',
			'change'   => 'filesystem_directory_failed_change',
		),
	),

	// File Events
	//
	'file' => array(

		// Fallback Events
		'fallback' => array(
			'move'     => 'filesystem_file_fallback_move',
			'make'     => 'filesystem_file_fallback_make',
			'delete'   => 'filesystem_file_fallback_delete',
			'rename'   => 'filesystem_file_fallback_rename',
			'contents' => 'filesystem_file_fallback_contents',
			'write'    => 'filesystem_file_fallback_write',
			'append'   => 'filesystem_file_fallback_append',
			'size'     => 'filesystem_file_fallback_size',
			'exists'   => 'filesystem_file_fallback_exists',
			'modified' => 'filesystem_file_fallback_modified',
		),

		// Failed Events
		'failed' => array(
			'move'     => 'filesystem_file_failed_move',
			'make'     => 'filesystem_file_failed_make',
			'delete'   => 'filesystem_file_failed_delete',
			'rename'   => 'filesystem_file_failed_rename',
			'contents' => 'filesystem_file_failed_contents',
			'write'    => 'filesystem_file_failed_write',
			'append'   => 'filesystem_file_failed_append',
			'size'     => 'filesystem_file_failed_size',
			'exists'   => 'filesystem_file_failed_exists',
			'modified' => 'filesystem_file_failed_modified',
		),
	),

);