<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class RoleVO
{
	public $username;
	public $roles;
	
	public function __construct ($username = '', array $roles = array ())
	{
		$this->username = $username;
		$this->roles = $roles;
	}
}