<?php

return array(

	'strategy' => 'Strategy fell back to the native driver.',

	'directory' => array(
		'move'     => 'Filesystem fellback to the native driver to move the directory.',
		'make'     => 'Filesystem fellback to the native driver to create the directory.',
		'delete'   => 'Filesystem fellback to the native driver to delete the directory.',
		'clean'    => 'Filesystem fellback to the native driver to clean the directory.',
		'rename'   => 'Filesystem fellback to the native driver to rename the directory.',
		'current'  => 'Filesystem fellback to the native driver to find the current directory.',
		'contents' => 'Filesystem fellback to the native driver to find the directories contents.',
		'change'   => 'Filesystem fellback to the native driver to change directories.',
	),

	'file' => array(
		'move'     => 'Filesystem fellback to the native driver to move the file.',
		'make'     => 'Filesystem fellback to the native driver to create a file.',
		'delete'   => 'Filesystem fellback to the native driver to delete a file.',
		'rename'   => 'Filesystem fellback to the native driver to rename a file.',
		'contents' => 'Filesystem fellback to the native driver to retrieve file contents.',
		'write'    => 'Filesystem fellback to the native driver to write contents to file.',
		'append'   => 'Filesystem fellback to the native driver to append contents to file.',
		'size'     => 'Filesystem fellback to the native driver to find the file size.',
		'exists'   => 'Filesystem fellback to the native driver check to see if the file exists.',
		'modified' => 'Filesystem fellback to the native driver to modify a file.',
	),

);