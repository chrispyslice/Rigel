<?php

/**
 * Project "Rigel" at DevXS
 * 
 * @license		CC BY-SA 3.0
 * @author		Chris Atkin-Granville
 * @link		http://chrisatk.in
 * @email		contact {at} chrisatk {dot} in
 * 
 * @file		expence.php
 * @version		1.0
 * @date		12/11/2011
 * 
 * Copyright (c) 2011 KSplitters
 */
 
class Model_Expense extends Orm\Model
{
	protected static $_table_name = 'Expense';

	// --------------------------------------------------------------------------

	protected static $_primary_key = array('id');

	// --------------------------------------------------------------------------

	protected static $_properties = array(
		'id'	=> array(
			'data_type'		=> 'int'
		),
		
		'group'	=> array(
			'data_type'		=> 'int'
		),
		
		'price'	=> array(
			'data_type'		=> 'int'
		),
		
		'quantity' => array(
			'data_type'		=> 'int'
		),
		
		'who_bought' => array(
			'data_type'		=> 'int'
		),
		
		'description' => array(
			'data_type' => 'text'
		)
	);
	
	// --------------------------------------------------------------------------
	
}
 
/* End of file expence.php */
/* Location: ./fuel/app/classes/model/expence.php */