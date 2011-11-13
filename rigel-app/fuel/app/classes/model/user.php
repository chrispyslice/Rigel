<?php

/**
 * Project "Rigel" at DevXS
 * 
 * @license		CC BY-SA 3.0
 * @author		Chris Atkin-Granville
 * @link		http://chrisatk.in
 * @email		contact {at} chrisatk {dot} in
 * 
 * @file		user.php
 * @version		1.0
 * @date		12/11/2011
 * 
 * Copyright (c) 2011 KSplitters
 */
 
class Model_User extends Orm\Model
{
	protected static $_table_name = 'User';
	
	// --------------------------------------------------------------------------
	
	protected static $_primary_key = array('id');
	
	// --------------------------------------------------------------------------
	
	protected static $_properties = array(
		'id' => array(
			'data_type' => 'int'
		),
		
		'username' => array(
			'data_type' => 'varchar'
		),
		
		'password' => array(
			'data_type' => 'varchar'
		),
		
		'group' => array(
			'data_type' => 'int'
		),
		
		'email' => array(
			'data_type' => 'varchar'
		),
		
		'last_login' => array(
			'data_type' => 'varchar'
		),
		
		'login_hash' => array(
			'data_type' => 'varchar'
		)
	);
	
	// --------------------------------------------------------------------------
	
	/**
	 * Many-to-many relation for a user belonging to many groups
	 */
	protected static $_many_many = array(
		'Group' => array(
			'key_from' => 'id',
			'key_through_from' => 'user_id',
			'table_through' => 'Membership',
			'key_through_to' => 'group_id',
			'model_to' => 'Model_Group',
			'key_to' => 'id'
		)
	);
	
	/**
	 * A user has many payments
	 */
	protected static $_has_many = array(
		'Expense' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Expense',
			'key_to' => 'who_bought'
		),
	
		'Payment' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Payment',
			'key_to' => 'user_id'
		)
	);
}

/* End of file user.php */
/* Location: ./fuel/app/classes/model/user.php */