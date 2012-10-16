<?php

return array(

	// Default Filesystem Driver
	//
	'default_driver' => 'ftp',

	// Event Name
	//
	'event' => array(
		'fallback' => 'filesystem_event_fallback',
		'failed'   => 'filesystem_event_failed',
	),

	// Fallback - Native only
	//
	'fallback' => true,

	// Driver Settings
	//
	'settings' => array(

		 // FTP Settings
		 //
		'ftp' => array(
			'server'   => '',
			'user'     => 'brunofgaspar@live.com.pt',
			'password' => 'asd',
			'port'     => 21,
			'timeout'  => 2,
		),
	)
);