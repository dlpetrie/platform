<?php

return array(

	// Default Filesystem Driver
	//
	'default_driver' => '{{driver}}',

	// Fallback - Native only
	//
	'fallback' => true,

	// Driver Settings
	//
	'settings' => array(

		 // FTP Settings
		 //
		'ftp' => array(
			'server'   => '{{ftp_server}}',
			'user'     => '{{ftp_user}}',
			'password' => '{{ftp_password}}',
			'port'     => {{ftp_port}},
			'timeout'  => {{ftp_timeout}},
		),
	)
);