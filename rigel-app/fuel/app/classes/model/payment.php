<?php

/**
 * Project "Rigel" at DevXS
 * 
 * @license		CC BY-SA 3.0
 * @author		Chris Atkin-Granville
 * @link		http://chrisatk.in
 * @email		contact {at} chrisatk {dot} in
 * 
 * @file		payment.php
 * @version		1.0
 * @date		12/11/2011
 * 
 * Copyright (c) 2011 KSplitters
 */
 
class Model_Payment extends Orm\Model
{
	protected static $_table_name = 'Payment';
	
	// --------------------------------------------------------------------------
	
	protected static $_primary_key = array('id');
	
	// --------------------------------------------------------------------------
	
	protected static $_properties = array(
		'id' => array(
			'data_type' => 'int'
		),
		
		/*'expense_id' => array(
			'data_type' => 'int'
		),*/
		
		'user_id' => array(
			'data_type' => 'int'
		),
		
		'amount_due' => array(
			'data_type' => 'int'
		),
		
		'amount_paid' => array(
			'data_type' => 'int'
		),
		
		'date_due' => array(
			'data_type' => 'date'
		),
		
		'date_paid' => array(
			'data_type' => 'date'
		)
	);
}
 
/* End of file payment.php */
/* Location: ./fuel/app/classes/model/payment.php */