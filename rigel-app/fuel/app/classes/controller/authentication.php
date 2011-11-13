<?php

/**
 * Project "Rigel" at DevXS
 * 
 * @license		CC BY-SA 3.0
 * @author		Chris Atkin-Granville
 * @link		http://chrisatk.in
 * @email		contact {at} chrisatk {dot} in
 * 
 * @file		authentication.php
 * @version		1.0
 * @date		13/11/2011
 * 
 * Copyright (c) 2011 KSplitters
 */
 
class Controller_Authentication extends Controller_Rest
{
	private $hasher;

	public function get_login($username, $password)
	{
		$auth = Auth::instance();
		$user_data = Format::factory(Model_User::find()->where('username', $username)->get())->to_array();
		if(!empty($user_data)) {
			session_start();
			//print_r($user_data);
			foreach($user_data as $data) {
				$id = $data['id'];
			}
			$_SESSION['user'] = $id;
			Output::redirect('dashboard');
		} else {
			echo "No user found";
		}
		
		//var_dump(Format::factory(Model_User::find()->where('username', $username)->where('password', base64_encode($this->hasher()->pbkdf2($password, Config::get('auth.salt'), 10000, 32))))->to_array());
	}
	
	public function hasher()
	{
		if ( ! class_exists('PHPSecLib\\Crypt_Hash', false))
		{
			import('phpseclib/Crypt/Hash', 'vendor');
		}
		
		is_null($this->hasher) and $this->hasher = new PHPSecLib\Crypt_Hash();

		return $this->hasher;
	}
}
	 
/* End of file authentication.php */
/* Location: ./fuel/app/classes/controller/authentication.php */