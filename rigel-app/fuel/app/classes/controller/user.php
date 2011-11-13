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
 
class Controller_User extends Controller_Rest
{
	public function get_list()
	{
		$this->response(array(
			// TODO: remove password from that removed
			Format::factory(Model_User::find('all'))->to_array()	
		));
	}
	
	public function get_detail($id)
	{
		$this->response(array(
			Format::factory(Model_User::find($id, array('related' => array('Group', 'Expense', 'Payment'))))->to_array()
		));
	}
	
	public function get_expenses($id)
	{
		$this->response(array(
			Format::factory(Model_User::find($id, array('related' => 'Expense')))->to_array()
		));
	}
	
	public function get_payments($id)
	{
		$this->response(array(
			Format::factory(Model_User::find($id, array('related' => 'Payment')))->to_array()
		));
	}	
}
 
/* End of file user.php */
/* Location: ./fuel/app/classes/controller/user.php */