<?php
/**
 * The development database settings.
 */

return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'	 => 'localhost',
			'port'		 => 3306,
			'database'	 => 'Rigel',
			'username'	 => 'root',
			'password'	 => '',
			'persistent' => false,
		),
		'table_prefix' => '',
		'identifier'   => '`',
		'charset'      => 'utf8',
		'caching'      => false,
		'profiling'    => false,
	),
);
