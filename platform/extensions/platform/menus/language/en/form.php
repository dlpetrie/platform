<?php

return array(

	'root' => array(
		'name' => 'Name',
		'slug' => 'Slug',
	),

	// Common
	'child' => array(
		'name'   => 'Name',
		'slug'   => 'Slug',
		'uri'    => 'Uri',
		'secure' => 'Make Secure (HTTPS)',
		'class'  => 'CSS class',
		'target' => array(
			'title'  => 'Target',
			'self'   => 'Same Window',
			'blank'  => 'New Window',
			'parent' => 'Parent Frame',
			'top'    => 'Top Frame (Main Document)',
		),

		'visibility' => array(
			'title'      => 'Visibility',
			'always'     => 'Show Always',
			'logged_in'  => 'Logged In',
			'logged_out' => 'Logged Out',
			'admin'      => 'Admin Only',
		),

		'yes'    => 'Yes',
		'no'     => 'No',
		'status' => 'Enabled',
	),

	// Create specific
	'create' => array(

		'child' => array(
			'legend' => 'New Child',
		),
	),

	// Update specific
	'update' => array(

		'child' => array(
			'legend' => 'Edit Child',
		),
	),

);
