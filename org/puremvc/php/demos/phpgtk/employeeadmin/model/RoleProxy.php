<?php
/**
 * PureMVC PHP Employee Admin Demo by Sasa Tarbuk <sasa@mindframes.org>
 * PureMVC - Copyright(c) 2006-2010 Futurescale, Inc., Some rights reserved.
 * Your reuse is governed by the Creative Commons Attribution 3.0 Unported License
 */

class RoleProxy extends Proxy
{
	const NAME = 'RoleProxy';
	public $roles;
	
	public function __construct ()
	{
		parent::__construct (self::NAME, new ArrayObject ());
		$this->roles = &$this->data;
	}
	
	public function addItem ($item)
	{
		$this->roles->offsetSet (null, $item);
	}
	
	public function deleteItem ($item)
	{
		foreach ($this->roles as $i => $roleVO)
		{
			if ($roleVO->username === $item->username)
			{
				$this->roles->offsetUnset ($i);
				return;
			}
		}
	}
	
	public function doesUserHaveRole (UserVO $user, $role)
	{
		foreach ($this->roles as $roleVO)
		{
			if ($roleVO->username === $user->username)
			{
				foreach ($roleVO->roles as $roleEnum)
				{
					if ($roleEnum === $role)
					{
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public function addRoleToUser (UserVO $user, $role)
	{
		if (!$this->doesUserHaveRole ($user, $role))
		{
			foreach ($this->roles as $VO => $roleVO)
			{
				if ($roleVO->username === $user->username)
				{
					array_push ($roleVO->roles, $role);
					$result = true;
					break;
				}
			}
		}
		$this->sendNotification (AppFacade::ADD_ROLE_RESULT, isset ($result), $role);
	}
	
	public function removeRoleFromUser (UserVO $user, $role)
	{
		if ($this->doesUserHaveRole ($user, $role))
		{
			foreach ($this->roles as $VO => $roleVO)
			{
				if ($roleVO->username === $user->username)
				{
					foreach ($roleVO->roles as $r => $roleEnum)
					{
						if ($roleEnum === $role)
						{
							unset ($roleVO->roles [$r]);
							break 2;
						}
					}
				}
			}
		}
	}
	
	public function getUserRoles ($username)
	{
		foreach ($this->roles as $VO => $roleVO)
		{
			if ($roleVO->username === $username)
			{
				return $roleVO->roles;
			}
		}
		return array ();
	}
}