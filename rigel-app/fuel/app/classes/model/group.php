<?php

/**
 * Project "Rigel" at DevXS
 * 
 * @license		CC BY-SA 3.0
 * @author		Chris Atkin-Granville
 * @link		http://chrisatk.in
 * @email		contact {at} chrisatk {dot} in
 * 
 * @file		group.php
 * @version		1.0
 * @date		12/11/2011
 * 
 * Copyright (c) 2011 KSplitters
 */
 
class Model_Group extends Orm\Model
{
	protected static $_table_name = 'Group';
	
	// --------------------------------------------------------------------------
	
	protected static $_primary_key = array('id');
	
	// --------------------------------------------------------------------------
	
	protected static $_properties = array(
		'id' => array(
			'data_type' => 'int'
		),
		
		'name' => array(
			'data_type' => 'varchar'
		),
		
		'description' => array(
			'data_type' => 'varchar'
		),
	);
	
	// --------------------------------------------------------------------------
	
	/**
	 * Many-to-many relationship
	 */
	protected static $_many_many = array(
	
	);
}

/* End of file group.php */
/* Location: ./fuel/app/classes/model/group.php */