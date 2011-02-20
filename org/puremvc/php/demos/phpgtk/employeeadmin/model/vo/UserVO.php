<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class UserVO
{
	public $username;
	public $fname;
	public $lname;
	public $email;
	public $password;
	public $department;
	
	public function __construct ($username = '', $fname = '', $lname = '', $email = '', $password = '', $department = -1)
	{
		$this->username = $username;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->email = $email;
		$this->password = $password;
		$this->department = $department;
	}
	
	public function isValid ()
	{
		return
		(
			$this->username &&
			$this->password &&
			$this->department > -1
		) ? true : false;
	}
	
	public function getGivenName ()
	{
		return $this->lname . ($this->lname && $this->fname ? ', ' : '') .  $this->fname;
	}
}