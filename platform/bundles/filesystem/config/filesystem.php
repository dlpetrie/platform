<?php

return array(

	// Default Filesystem Driver
	//
	'default_driver' => 'native',

	// Fallback - Native only
	//
	'fallback' => true,

	// Driver Settings
	//
	'settings' => array(

		 // FTP Settings
		 //
		'ftp' => array(
			'server'   => null,
			'user'     => null,
			'password' => null,
			'port'     => 21,
			'timeout'  => 90,
		),
	)
);