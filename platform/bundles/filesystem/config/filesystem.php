<?php

return array(

	// Default Filesystem Driver
	//
	'default_driver' => 'ftp',

	// Fallback - Native only
	//
	'fallback' => true,

	// Driver Settings
	//
	'settings' => array(

		 // FTP Settings
		 //
		'ftp' => array(
			'server'   => 'localhost',
			'user'     => 'platform',
			'password' => 'test',
			'port'     => 21,
			'timeout'  => 90,
		),
	)
);