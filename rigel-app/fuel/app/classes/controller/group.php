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

class Controller_Group extends Controller_Rest
{
	public function get_list()
	{	
		$this->response(array(
			Format::factory(Model_Group::find('all'))->to_array()
		));
	}
	
	// --------------------------------------------------------------------------
	
	public function get_detail($id)
	{
		$this->response(array(
			Format::factory(Model_Group::find($id))->to_array()
		));
	}
	
	public function get_expenses($id)
	{
		$this->response(array(
			Format::factory(Model_Expense::find()->where('group', $id)->get())->to_array()
		));
	}
}

/* End of file group.php */
/* Location: ./fuel/app/classes/controller/group.php */